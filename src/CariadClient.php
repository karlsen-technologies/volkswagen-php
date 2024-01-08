<?php

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Client;
use KarlsenTechnologies\Volkswagen\DataObjects\Api\AuthenticationRedirect;

class CariadClient
{

    const BASE_URL = 'https://emea.bff.cariad.digital';

    protected Client $client;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getVWAuthenticationUrl(): ?AuthenticationRedirect
    {
        $rand64bit = strval(mt_rand() << 32 | mt_rand()) . strval(time());

        $response = $this->client->get('/user-login/v1/authorize',
            [
                'query' => [
                    'redirect_uri' => 'weconnect://authenticated',
                    'nonce' => $rand64bit,
                ],
                'allow_redirects' => false,
            ]
        );

        if ($response->getStatusCode() === 303) {
            return new AuthenticationRedirect($response->getHeader('Location')[0]);
        }

        return null;
    }

}
