<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\CommandResponse;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait ManagesVehicleCharging
{
    /**
     * @throws GuzzleException
     */
    public function startCharging(Vehicle|string $vehicle): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/charging/start");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }

    public function stopCharging(Vehicle|string $vehicle): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/charging/stop");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }
}
