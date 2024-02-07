<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Actions\Vehicle;

use GuzzleHttp\Exception\GuzzleException;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status\Domain;
use KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Vehicle;
use KarlsenTechnologies\Volkswagen\Enums\Vehicle\StatusDomain;

/**
 * Trait GetsVehicleStatus
 *
 * @package KarlsenTechnologies\Volkswagen\Actions
 * @mixin \KarlsenTechnologies\Volkswagen\WeConnectClient
 */
trait GetsVehicleStatus
{
    /**
     * @throws GuzzleException
     */
    public function vehicleStatus(Vehicle|string $vehicle, ?array $domains = null): array
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        if ($domains !== null) {
            foreach ($domains as $key => $domain) {
                if ($domain instanceof StatusDomain) {
                    $domains[$key] = $domain->value;
                }
            }
        } else {
            $domains = StatusDomain::values();
        }

        $response = $this->get("/vehicle/v1/vehicles/{$vehicle}/selectivestatus?jobs=" . implode(',', $domains));

        $responseContents = $response->body;

        $data = json_decode($responseContents, true);

        $domains = [];

        foreach($data as $domainName => $domainData) {
            $domains[$domainName] = Domain::fromApi($domainName, $domainData);
        }

        return $domains;
    }
}
