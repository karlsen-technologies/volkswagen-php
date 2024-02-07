<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Climatisation;

use KarlsenTechnologies\Volkswagen\Enums\Vehicle\ClimatisationTemperatureUnit;

class ClimatisationSettings
{
    public function __construct(
        public ?bool $climatizationAtUnlock = null,
        public ?int $targetTemperature = null,
        public ?ClimatisationTemperatureUnit $targetTemperatureUnit = null,
        public ?bool $windowHeatingEnabled = null,
        public mixed $auxiliaryHeatingSettings = null,
        public ?bool $zoneFrontLeftEnabled = null,
        public ?bool $zoneFrontRightEnabled = null,
        public ?bool $zoneRearLeftEnabled = null,
        public ?bool $zoneRearRightEnabled = null,
        public ?bool $climatisationWithoutExternalPower = null,
    ) {}

    public static function fromApi(array $data): ClimatisationSettings
    {
        $targetTemperatureUnit = ClimatisationTemperatureUnit::tryFrom($data['targetTemperatureUnit']);

        return new ClimatisationSettings(
            $data['climatizationAtUnlock'],
            $data['targetTemperature'],
            $targetTemperatureUnit,
            $data['windowHeatingEnabled'],
            $data['auxiliaryHeatingSettings'],
            $data['zoneFrontLeftEnabled'],
            $data['zoneFrontRightEnabled'],
            $data['zoneRearLeftEnabled'],
            $data['zoneRearRightEnabled'],
            $data['climatisationWithoutExternalPower'],
        );
    }

    public function toApi(): array
    {
        return array_filter([
            'climatizationAtUnlock' => $this->climatizationAtUnlock,
            'targetTemperature' => $this->targetTemperature,
            'targetTemperatureUnit' => $this->targetTemperatureUnit?->value,
            'windowHeatingEnabled' => $this->windowHeatingEnabled,
            'auxiliaryHeatingSettings' => $this->auxiliaryHeatingSettings,
            'zoneFrontLeftEnabled' => $this->zoneFrontLeftEnabled,
            'zoneFrontRightEnabled' => $this->zoneFrontRightEnabled,
            'zoneRearLeftEnabled' => $this->zoneRearLeftEnabled,
            'zoneRearRightEnabled' => $this->zoneRearRightEnabled,
            'climatisationWithoutExternalPower' => $this->climatisationWithoutExternalPower,
        ]);
    }

    public static function fromArray(array $data): ClimatisationSettings
    {
        $targetTemperatureUnit = ClimatisationTemperatureUnit::from($data['targetTemperatureUnit']);

        return new ClimatisationSettings(
            $data['climatization_at_unlock'],
            $data['target_temperature'],
            $targetTemperatureUnit,
            $data['window_heating_enabled'],
            $data['auxiliary_heating_settings'],
            $data['zone_front_left_enabled'],
            $data['zone_front_right_enabled'],
            $data['zone_rear_left_enabled'],
            $data['zone_rear_right_enabled'],
            $data['climatisation_without_external_power'],
        );
    }

    public function toArray(): array
    {
        return [
            'climatization_at_unlock' => $this->climatizationAtUnlock,
            'target_temperature' => $this->targetTemperature,
            'target_temperature_unit' => $this->targetTemperatureUnit?->value,
            'window_heating_enabled' => $this->windowHeatingEnabled,
            'auxiliary_heating_settings' => $this->auxiliaryHeatingSettings,
            'zone_front_left_enabled' => $this->zoneFrontLeftEnabled,
            'zone_front_right_enabled' => $this->zoneFrontRightEnabled,
            'zone_rear_left_enabled' => $this->zoneRearLeftEnabled,
            'zone_rear_right_enabled' => $this->zoneRearRightEnabled,
            'climatisation_without_external_power' => $this->climatisationWithoutExternalPower,
        ];
    }
}
