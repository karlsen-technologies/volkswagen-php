<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use DOMElement;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Api\AuthenticationForm;
use KarlsenTechnologies\Volkswagen\DataObjects\Http\Response;
use KarlsenTechnologies\Volkswagen\DataObjects\IdentityCredentials;
use DOMDocument;
use Exception;

class IdentityClient extends BaseClient
{
    protected CariadClient $cariadClient;

    protected array $headers = [
        'Content-Version' => '1',
        'User-Agent' => 'WeConnect/3 CFNetwork/1331.0.7 Darwin/21.4.0',
        'Cache-Control' => 'no-cache',
        'Pragma' => 'no-cache',
        'Accept' => '*/*',
    ];

    protected array $options = [
        'allow_redirects' => false,
    ];

    public function __construct(string $baseUrl = 'https://identity.vwgroup.io', array $headers = [], ?CariadClient $cariadClient = null)
    {
        $this->baseUrl = $baseUrl;
        $this->headers = array_merge($this->headers, $headers);

        parent::__construct();

        $this->cariadClient = $cariadClient ?? new CariadClient();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function authorize(string $email, string $password): IdentityCredentials
    {
        // We start by getting the authentication url from Cariad.
        // This is used as a simplification by the We Connect app to build the full url required for VW Identity.
        $authenticationRedirectUrl = $this->cariadClient->getVWAuthenticationUrl();

        // Start the authorization process with VW Identity. This will redirect us to the login page.
        // VW has split the login process into two steps, first we submit the email address, then we submit the password.
        $emailFormRedirectUrl = $this->startAuthorization($authenticationRedirectUrl);

        // Send a request to the email form url to get the form parameters.
        // Because we are unable to the change the final redirect url, we need to pretend to be the user and directly submit the forms.
        $emailForm = $this->getEmailForm($emailFormRedirectUrl);

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

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    protected function startAuthorization(string $redirectUrl): string
    {
        $response = $this->get($redirectUrl);

        if ($response->statusCode === 302) {
            return $response->header('location')[0];
        }

        throw new Exception('Failed to start authorization');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    protected function getEmailForm(string $redirectUrl): AuthenticationForm
    {
        $response = $this->get($redirectUrl);

        $responseContents = $response->body;

        $document = new DOMDocument();

        $document->loadHTML($responseContents);

        $loginForm = $document->getElementById('emailPasswordForm');

        if($loginForm === null) {
            throw new Exception('Failed to find email form');
        }

        $formTarget = $loginForm->getAttribute('action');

        $emailFormParameters = [];

        /** @var DOMElement $childNode */
        foreach($loginForm->childNodes as $childNode) {
            if($childNode->nodeName !== 'input') {
                continue;
            }

            $emailFormParameters[$childNode->getAttribute('name')] = $childNode->getAttribute('value');
        }

        return new AuthenticationForm($formTarget, $emailFormParameters);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    protected function submitEmailForm(AuthenticationForm $form): string
    {
        $response = $this->post(
            $form->targetUrl,
            [
                'form_params' => $form->parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => '*/*',
                ]
            ]
        );

        if ($response->statusCode === 303) {
            return $response->header('location')[0];
        }

        throw new Exception('Failed to submit email form');
    }

    protected function getPasswordForm(string $redirectUrl): AuthenticationForm
    {
        $response = $this->get($redirectUrl);

        $responseContents = $response->body;

        $document = new DOMDocument();

        $document->loadHTML($responseContents);

        $templateModel = null;
        $csrfToken = null;

        foreach($document->getElementsByTagName('script') as $node) {
            if ($node->nodeValue === null) {
                continue;
            }

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

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    protected function submitPasswordForm(AuthenticationForm $form): string
    {
        // We need to follow the redirects until we get the final redirect url for the app.
        // The final redirect url uses a custom scheme, which will cause Guzzle to throw an exception that we need to catch.
        try {
            $this->post(
                $form->targetUrl,
                [
                    'form_params' => $form->parameters,
                    'allow_redirects' => true,
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => '*/*',
                    ]
                ]
            );
        } catch (BadResponseException $e) {
            $response = Response::fromGuzzleResponse($e->getResponse());

            if ($response->statusCode === 302) {
                return $response->header('location')[0];
            }
        }

        throw new Exception('Failed to submit password form');
    }

    /**
     * @throws Exception
     */
    protected function parseAppRedirect(string $redirectUrl): IdentityCredentials
    {
        $parameters = parse_url($redirectUrl, PHP_URL_FRAGMENT);

        if (!is_string($parameters)) {
            throw new Exception('Invalid parameters');
        }

        parse_str($parameters, $query_params);

        return IdentityCredentials::fromArray($query_params);
    }
}
