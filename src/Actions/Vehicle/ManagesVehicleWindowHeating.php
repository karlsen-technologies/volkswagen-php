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
trait ManagesVehicleWindowHeating
{
    /**
     * @throws GuzzleException
     */
    public function startWindowHeating(Vehicle|string $vehicle): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/windowheating/start");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }

    public function stopWindowHeating(Vehicle|string $vehicle): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/windowheating/stop");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }
}
