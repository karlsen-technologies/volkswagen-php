<?php

namespace KarlsenTechnologies\Volkswagen\Actions;

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
    public function vehicleStatus(Vehicle|string $vehicle, ?array $domains = null)
    {
        if ($vehicle instanceof Vehicle) {
            $vehicle = $vehicle->vin;
        }

        if (! is_null($domains)) {
            foreach ($domains as $key => $domain) {
                if ($domain instanceof StatusDomain) {
                    $domains[$key] = $domain->value;
                }
            }
        } else {
            $domains = StatusDomain::values();
        }

        $response = $this->httpClient->get("/vehicle/v1/vehicles/$vehicle/selectivestatus?jobs=" . implode(',', $domains));

        $responseContents = $response->getBody()->getContents();

        $data = json_decode($responseContents, true);

        $domains = [];

        foreach($data as $domainName => $domainData) {
            $domains[$domainName] = Domain::fromApi($domainName, $domainData);
        }

        return $domains;
    }
}
