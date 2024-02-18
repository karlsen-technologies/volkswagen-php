<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\PendingRequest;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait GetsVehiclePendingRequests
{
    /**
     * @throws GuzzleException
     */
    public function vehiclePendingRequests(Vehicle|string $vehicle): array
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->get("/vehicle/v1/vehicles/{$vehicle}/pendingrequests");

        if ($response->statusCode === 204) {
            return [];
        }

        $responseContents = $response->body;

        $data = json_decode($responseContents, true) ?? [];

        $pendingRequests = [];

        foreach ($data['data'] as $request) {
            $pendingRequests[] = PendingRequest::fromApi($request);
        }

        return $pendingRequests;
    }
}
