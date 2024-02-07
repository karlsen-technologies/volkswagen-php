<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Capability\Capability;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait GetsVehicleCapabilities
{
    /**
     * @throws GuzzleException
     */
    public function vehicleCapabilities(Vehicle|string $vehicle): array
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->get("/vehicle/v1/vehicles/{$vehicle}/capabilities");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        $capabilities = [];

        foreach ($data['capabilities'] as $capability) {
            $capabilities[] = Capability::fromApi($capability);
        }

        return $capabilities;
    }
}
