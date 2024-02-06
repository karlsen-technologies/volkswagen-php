<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use Exception;
use KarlsenTechnologies\Volkswagen\DataObjects\Http\Response;
use Psr\Http\Message\ResponseInterface;

class BaseClient
{
    public string $baseUrl = '';

    protected HttpClient $guzzle;

    protected array $headers = [];

    protected array $options = [];

    protected CookieJar $cookieJar;

    public function __construct()
    {
        $this->cookieJar = new CookieJar();

        $this->refreshHttpClient();
    }

    public function refreshHttpClient(): HttpClient
    {
        $config = array_merge(
            [
                'base_uri' => $this->baseUrl,
                'headers' => $this->headers,
                'cookies' => $this->cookieJar,
            ],
            $this->options
        );

        $this->guzzle = new HttpClient($config);

        return $this->guzzle;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    protected function request(string $verb, string $uri, array $options = []): Response
    {
        $response = $this->guzzle->request($verb, $uri, $options);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 399) {
            $this->handleRequestError($response);
        }

        return Response::fromGuzzleResponse($response);
    }

    /**
     * @throws Exception
     */
    protected function handleRequestError(ResponseInterface $response): void
    {
        throw new Exception((string) $response->getBody());
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function put(string $uri, array $options = []): Response
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->request('DELETE', $uri, $options);
    }
}
