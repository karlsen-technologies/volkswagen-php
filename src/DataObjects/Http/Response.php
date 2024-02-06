<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Http;

use Psr\Http\Message\ResponseInterface;

class Response
{
    public function __construct(
        public int $statusCode,
        public array $headers,
        public string $body,
        protected ResponseInterface $response,
    ) {}

    public static function fromGuzzleResponse(ResponseInterface $response): self
    {
        return new self(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()->getContents(),
            $response
        );
    }

    public function toArray(): array
    {
        return [
            'statusCode' => $this->statusCode,
            'headers' => $this->headers,
            'body' => $this->body,
        ];
    }

    public function header(string $header): array
    {
        return $this->response->getHeader($header);
    }
}
