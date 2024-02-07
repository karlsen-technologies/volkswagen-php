<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Climatisation\ClimatisationSettings;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\CommandResponse;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait ManagesVehicleClimatisation
{
    /**
     * @throws GuzzleException
     */
    public function startClimatisation(Vehicle|string $vehicle, ClimatisationSettings|array|null $settings = null): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        if ($settings instanceof ClimatisationSettings) {
            $settings = $settings->toApi();
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/climatisation/start", [
            'json' => $settings ?? [],
        ]);

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }

    public function stopClimatisation(Vehicle|string $vehicle): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/climatisation/stop");

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }

    public function updateClimatisationSettings(Vehicle|string $vehicle, ClimatisationSettings|array|null $settings = null): CommandResponse
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        if ($settings instanceof ClimatisationSettings) {
            $settings = $settings->toApi();
        }

        $response = $this->post("/vehicle/v1/vehicles/{$vehicle}/climatisation/settings", [
            'json' => $settings ?? [],
        ]);

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        return CommandResponse::fromApi($data);
    }
}
