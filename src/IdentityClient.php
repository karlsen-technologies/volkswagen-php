<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\BadResponseException;
use KarlsenTechnologies\Volkswagen\DataObjects\Api\AuthenticationForm;
use KarlsenTechnologies\Volkswagen\DataObjects\Api\AuthenticationRedirect;
use KarlsenTechnologies\Volkswagen\DataObjects\IdentityCredentials;
use DOMDocument;

class IdentityClient
{
    protected CookieJar $cookieJar;

    protected Client $httpClient;

    protected CariadClient $cariadClient;

    protected string $baseUrl;

    protected array $headers = [
        'Content-Version' => '1',
        'User-Agent' => 'WeConnect/3 CFNetwork/1331.0.7 Darwin/21.4.0',
        'Cache-Control' => 'no-cache',
        'Pragma' => 'no-cache',
        'Accept' => '*/*',
    ];

    public function __construct(string $baseUrl = 'https://identity.vwgroup.io', array $headers = [], ?CariadClient $cariadClient = null)
    {
        $this->baseUrl = $baseUrl;
        $this->headers = array_merge($this->headers, $headers);

        // We need to store cookies between requests to VW Identity.
        // This is because we need to pretend to be the user and submit the login forms directly.
        // VW Identity uses cookies to track the state of the login process.
        $this->cookieJar = new CookieJar();

        $this->httpClient = new Client([
            'allow_redirects' => false,
            'base_uri' => $this->baseUrl,
            'cookies' => $this->cookieJar,
            'headers' => $this->headers,
        ]);

        $this->cariadClient = $cariadClient ?? new CariadClient();
    }

    public function authorize(string $email, string $password): IdentityCredentials
    {
        // We start by getting the authentication url from Cariad.
        // This is used as a simplification by the We Connect app to build the full url required for VW Identity.
        $authenticationRedirect = $this->cariadClient->getVWAuthenticationUrl();

        // Start the authorization process with VW Identity. This will redirect us to the login page.
        // VW has split the login process into two steps, first we submit the email address, then we submit the password.
        $emailFormRedirect = $this->startAuthorization($authenticationRedirect);

        // Send a request to the email form url to get the form parameters.
        // Because we are unable to the change the final redirect url, we need to pretend to be the user and directly submit the forms.
        $emailForm = $this->getEmailForm($emailFormRedirect);

        // Add the email address to the form parameters.
        $emailForm->parameters['email'] = $email;

        // Submit the email form and get the redirect url for the password form.
        $passwordFormRedirect = $this->submitEmailForm($emailForm);

        // Send a request to the password form url to get the form parameters.
        $passwordForm = $this->getPasswordForm($passwordFormRedirect);

        // Add the password to the form parameters.
        $passwordForm->parameters['password'] = $password;

        // Submit the password form and get the redirect url for the app.
        $appRedirect = $this->submitPasswordForm($passwordForm);

        // Parse the app redirect url to get the client credentials.
        return $this->parseAppRedirect($appRedirect);
    }

    protected function startAuthorization(AuthenticationRedirect $redirect): ?AuthenticationRedirect
    {
        $response = $this->httpClient->get($redirect->url);

        if ($response->getStatusCode() === 302) {
            return new AuthenticationRedirect($response->getHeader('Location')[0]);
        }

        return null;
    }

    protected function getEmailForm(AuthenticationRedirect $redirect): AuthenticationForm
    {
        $response = $this->httpClient->get($redirect->url);

        $responseContents = $response->getBody()->getContents();

        $document = new DOMDocument();

        $document->loadHTML($responseContents);

        $loginForm = $document->getElementById('emailPasswordForm');

        $formTarget = $loginForm->getAttribute('action');

        $emailFormParameters = [];

        foreach($loginForm->childNodes as $childNode) {
            if($childNode->nodeName !== 'input') {
                continue;
            }

            $emailFormParameters[$childNode->getAttribute('name')] = $childNode->getAttribute('value');
        }

        return new AuthenticationForm($formTarget, $emailFormParameters);
    }

    protected function submitEmailForm(AuthenticationForm $form): ?AuthenticationRedirect
    {
        $response = $this->httpClient->post(
            $form->targetUrl,
            [
                'form_params' => $form->parameters,
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => '*/*',
                ]
            ]
        );

        if ($response->getStatusCode() === 303) {
            return new AuthenticationRedirect($response->getHeader('Location')[0]);
        }

        return null;
    }

    protected function getPasswordForm(AuthenticationRedirect $redirect): AuthenticationForm
    {
        $response = $this->httpClient->get($redirect->url);

        $responseContents = $response->getBody()->getContents();

        $document = new DOMDocument();

        $document->loadHTML($responseContents);

        $templateModel = null;
        $csrfToken = null;

        foreach($document->getElementsByTagName('script') as $node) {
            if(! str_contains($node->nodeValue, 'window._IDK =')) {
                continue;
            }

            preg_match('/(?<=templateModel: ).*(?=,)/', $node->nodeValue, $matches);
            $templateModel = json_decode($matches[0], true);
            unset($matches);

            preg_match("/(?<=csrf_token: ').*(?=',)/", $node->nodeValue, $matches);
            $csrfToken = $matches[0];
            unset($matches);
        }

        return new AuthenticationForm($this->baseUrl . '/signin-service/v1/' . $templateModel['clientLegalEntityModel']['clientId'] . '/' . $templateModel['postAction'], [
            'relayState' => $templateModel['relayState'],
            '_csrf' => $csrfToken,
            'hmac' => $templateModel['hmac'],
            'email' => $templateModel['emailPasswordForm']['email']
        ]);
    }

    protected function submitPasswordForm(AuthenticationForm $form): ?AuthenticationRedirect
    {
        try {
            $this->httpClient->post(
                $form->targetUrl,
                [
                    'form_params' => $form->parameters,
                    'allow_redirects' => true,
                    'http_errors' => false,
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => '*/*',
                    ]
                ]
            );
        } catch (BadResponseException $e) {
            // If the response in the exception was a "Found" redirect, we will extract the redirect url for further processing.
            if ($e->getResponse()->getStatusCode() === 302) {
                return new AuthenticationRedirect($e->getResponse()->getHeader('Location')[0]);
            }
        }

        return null;
    }

    protected function parseAppRedirect(AuthenticationRedirect $redirect): IdentityCredentials
    {
        parse_str(parse_url($redirect->url, PHP_URL_FRAGMENT), $query_params);

        return IdentityCredentials::fromArray($query_params);
    }
}
