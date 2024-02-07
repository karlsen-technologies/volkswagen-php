<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Parking;

class ParkingPosition
{
    public function __construct(
        public float $lat,
        public float $lon,
        public string $carCapturedTimestamp,
    ) {}

    public static function fromApi(array $data): ParkingPosition
    {
        $data = $data['data'];

        return new ParkingPosition(
            $data['lat'],
            $data['lon'],
            $data['carCapturedTimestamp'],
        );
    }

    public static function fromArray(array $data): ParkingPosition
    {
        return new ParkingPosition(
            $data['lat'],
            $data['lon'],
            $data['car_captured_timestamp'],
        );
    }

    public function toArray(): array
    {
        return [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'car_captured_timestamp' => $this->carCapturedTimestamp,
        ];
    }
}
