<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait ListsVehicles
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait ListsVehicles
{
    /**
     * @throws GuzzleException
     */
    public function vehicles(): array
    {
        $response = $this->get('/vehicle/v1/vehicles');

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        $vehicles = [];

        foreach ($data['data'] as $vehicleData) {
            $vehicles[] = Vehicle::fromArray($vehicleData);
        }

        return $vehicles;
    }
}
