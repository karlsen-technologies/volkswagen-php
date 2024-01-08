<?php

namespace kartech\Volkswagen;

use GuzzleHttp\Client;
use kartech\Volkswagen\DataObjects\IdentityCredentials;
use kartech\Volkswagen\DataObjects\Vehicle\Status\Domain;
use kartech\Volkswagen\DataObjects\Vehicle\Vehicle;
use kartech\Volkswagen\DataObjects\WeConnectCredentials;
use kartech\Volkswagen\Enums\Vehicle\StatusDomain;

class WeConnectClient
{
    protected ?WeConnectCredentials $credentials;

    protected Client $httpClient;

    protected string $baseUrl;

    protected array $headers = [
        'Content-Version' => '1',
        'User-Agent' => 'WeConnect/3 CFNetwork/1331.0.7 Darwin/21.4.0',
        'Cache-Control' => 'no-cache',
        'Pragma' => 'no-cache',
        'Accept' => '*/*',
    ];

    public function __construct(?WeConnectCredentials $credentials = null, string $baseUrl = 'https://emea.bff.cariad.digital', array $headers = []) {
        $this->credentials = $credentials;

        if(! is_null($this->credentials)) {
            $this->headers['Authorization'] = 'Bearer ' . $this->credentials->accessToken;
        }

        $this->baseUrl = $baseUrl;
        $this->headers = array_merge($this->headers, $headers);

        $this->httpClient = new Client([
            'allow_redirects' => false,
            'base_uri' => $this->baseUrl,
            'headers' => $this->headers,
        ]);
    }

    public function login(IdentityCredentials $credentials): WeConnectCredentials
    {
        $response = $this->httpClient->post('/user-login/login/v1',
            [
                'json' => [
                    'state' => $credentials->state,
                    'id_token' => $credentials->idToken,
                    'redirect_uri' => 'weconnect://authenticated',
                    'region' => 'emea',
                    'access_token' => $credentials->accessToken,
                    'authorizationCode' => $credentials->code,
                ],
                'headers' => array_merge($this->headers, [
                    'Authorization' => null,
                ]),
            ]
        );

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $this->useCredentials(WeConnectCredentials::fromArray($data));

        return $this->credentials;
    }

    public function getCredentials(): ?WeConnectCredentials
    {
        return $this->credentials;
    }

    public function useCredentials(WeConnectCredentials $credentials): WeConnectClient
    {
        $this->credentials = $credentials;

        $this->headers['Authorization'] = 'Bearer ' . $this->credentials->accessToken;

        $this->httpClient = new Client([
            'allow_redirects' => false,
            'base_uri' => $this->baseUrl,
            'headers' => $this->headers,
        ]);

        return $this;
    }

    public function refreshCredentials(): WeConnectCredentials
    {
        $response = $this->httpClient->get('/user-login/refresh/v1',
            [
                'headers' => array_merge($this->headers, [
                    'Authorization' => 'Bearer ' . $this->credentials?->refreshToken,
                ]),
            ]
        );

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $this->useCredentials(WeConnectCredentials::fromArray($data));

        return $this->credentials;
    }

    public function vehicles()
    {
        $response = $this->httpClient->get('/vehicle/v1/vehicles');

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $vehicles = [];

        foreach ($data['data'] as $vehicleData)
        {
            $vehicles[] = Vehicle::fromArray($vehicleData);
        }

        return $vehicles;
    }

    public function vehicleStatus(Vehicle|string $vehicle, ?array $domains = null)
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        if (is_null($domains)) {
            $domains = StatusDomain::values();
        }

        $response = $this->httpClient->get("/vehicle/v1/vehicles/$vehicle/selectivestatus?jobs=" . implode(',', $domains));

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $domains = [];

        foreach($data as $domainName => $domainData) {
            $domains[$domainName] = Domain::fromApi($domainName, $domainData);
        }

        return $domains;
    }
}
