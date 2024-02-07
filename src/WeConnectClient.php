<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\IdentityCredentials;
use KarlsenTechnologies\Volkswagen\DataObjects\WeConnectCredentials;

class WeConnectClient extends BaseClient
{
    use Actions\Vehicle\GetsVehicleCapabilities;
    use Actions\Vehicle\GetsVehicleParkingPosition;
    use Actions\Vehicle\GetsVehiclePendingRequests;
    use Actions\Vehicle\GetsVehicleStatus;
    use Actions\Vehicle\ListsVehicles;
    use Actions\Vehicle\ManagesVehicleCharging;
    use Actions\Vehicle\ManagesVehicleClimatisation;
    use Actions\Vehicle\ManagesVehicleWindowHeating;
    use Actions\Vehicle\WakesUpVehicle;


    protected ?WeConnectCredentials $credentials;

    protected array $headers = [
        'Content-Version' => '1',
        'User-Agent' => 'WeConnect/3 CFNetwork/1331.0.7 Darwin/21.4.0',
        'Cache-Control' => 'no-cache',
        'Pragma' => 'no-cache',
        'Accept' => '*/*',
    ];

    public function __construct(?WeConnectCredentials $credentials = null, string $baseUrl = 'https://emea.bff.cariad.digital', array $headers = [])
    {
        $this->credentials = $credentials;

        if($this->credentials !== null) {
            $this->headers['Authorization'] = 'Bearer ' . $this->credentials->accessToken;
        }

        $this->baseUrl = $baseUrl;
        $this->headers = array_merge($this->headers, $headers);

        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    public function login(IdentityCredentials $credentials): ?WeConnectCredentials
    {
        $response = $this->post(
            '/user-login/login/v1',
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

        $responseContents = $response->body;

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

        $this->refreshHttpClient();

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function refreshCredentials(): ?WeConnectCredentials
    {
        $response = $this->get(
            '/user-login/refresh/v1',
            [
                'headers' => array_merge($this->headers, [
                    'Authorization' => 'Bearer ' . $this->credentials?->refreshToken,
                ]),
            ]
        );

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        $this->useCredentials(WeConnectCredentials::fromArray($data));

        return $this->credentials;
    }
}
