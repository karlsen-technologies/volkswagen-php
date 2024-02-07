<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;
use KarlsenTechnologies\Volkswagen\Exceptions\Vehicle\FailedCommandException;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait WakesUpVehicle
{
    /**
     * @throws GuzzleException
     */
    public function wakeupVehicle(Vehicle|string $vehicle): void
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/vehiclewakeuptrigger");

        if ($response->statusCode !== 204) {
            throw new FailedCommandException('Failed to trigger vehicle wakeup');
        }
    }
}
