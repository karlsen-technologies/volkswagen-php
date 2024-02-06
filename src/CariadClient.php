<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Exception\GuzzleException;
use Exception;

class CariadClient extends BaseClient
{
    public string $baseUrl = 'https://emea.bff.cariad.digital';

    protected array $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getVWAuthenticationUrl(): string
    {
        $nonce = $this->generateNonce();

        $response = $this->get(
            '/user-login/v1/authorize',
            [
                'query' => [
                    'redirect_uri' => 'weconnect://authenticated',
                    'nonce' => $nonce,
                ],
                'allow_redirects' => false,
            ]
        );

        if ($response->statusCode === 303) {
            return $response->header('Location')[0];
        }

        throw new Exception('Failed to get VW authentication URL');
    }

    private function generateNonce(): string
    {
        return strval(mt_rand() << 32 | mt_rand()) . strval(time());
    }

}
