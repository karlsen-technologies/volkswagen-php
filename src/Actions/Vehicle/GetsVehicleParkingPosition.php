<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Parking\ParkingPosition;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait GetsVehicleParkingPosition
{
    /**
     * @throws GuzzleException
     */
    public function vehicleParkingPosition(Vehicle|string $vehicle): ?ParkingPosition
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->get("/vehicle/v1/vehicles/{$vehicle}/parkingposition");

        // If the vehicle is not parked, the API will return a 204 No Content response
        if ($response->statusCode === 204) {
            return null;
        }

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return ParkingPosition::fromApi($data);
    }
}
