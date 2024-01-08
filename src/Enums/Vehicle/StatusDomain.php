<?php

namespace KarlsenTechnologies\Volkswagen\Enums\Vehicle;

enum StatusDomain: string
{
    case All = 'all';

    case Access = 'access';

    case ActiveVentilation = 'activeVentilation';

    case AuxiliaryHeating = 'auxiliaryHeating';

    case BatteryChargingCare = 'batteryChargingCare';

    case BatterySupport = 'batterySupport';

    case Charging = 'charging';

    case ChargingProfiles = 'chargingProfiles';

    case Climatisation = 'climatisation';

    case ClimatisationTimers = 'climatisationTimers';

    case DepartureProfiles = 'departureProfiles';

    case FuelStatus = 'fuelStatus';

    case HonkAndFlash = 'honkAndFlash';

    case HybridCarAuxiliaryHeating = 'hybridCarAuxiliaryHeating';

    case UserCapabilities = 'userCapabilities';

    case VehicleHealthWarnings = 'vehicleHealthWarnings';

    case VehicleHealthInspection = 'vehicleHealthInspection';

    case VehicleLights = 'vehicleLights';

    case Measurements = 'measurements';

    case DepartureTimers = 'departureTimers';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
