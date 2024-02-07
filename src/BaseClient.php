<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use Exception;
use KarlsenTechnologies\Volkswagen\DataObjects\Http\Response;
use KarlsenTechnologies\Volkswagen\Exceptions\BadRequestException;
use KarlsenTechnologies\Volkswagen\Exceptions\NotFoundException;
use KarlsenTechnologies\Volkswagen\Exceptions\UnauthorizedException;
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
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws GuzzleException
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
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    protected function handleRequestError(ResponseInterface $response): void
    {
        match($response->getStatusCode()) {
            400 => throw new BadRequestException($response->getBody()->getContents()),
            401 => throw new UnauthorizedException($response->getBody()->getContents()),
            404 => throw new NotFoundException(),
            default => throw new Exception((string) $response->getBody()),
        };
    }

    /**
     * @param string $uri
     * @param array $options
     * @return Response
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return Response
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return Response
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function put(string $uri, array $options = []): Response
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return Response
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->request('DELETE', $uri, $options);
    }
}
