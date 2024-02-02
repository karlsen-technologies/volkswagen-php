<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions;

use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait ListsVehicles
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait ListsVehicles
{
    public function vehicles(): array
    {
        $response = $this->httpClient->get('/vehicle/v1/vehicles');

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $vehicles = [];

        foreach ($data['data'] as $vehicleData) {
            $vehicles[] = Vehicle::fromArray($vehicleData);
        }

        return $vehicles;
    }
}
