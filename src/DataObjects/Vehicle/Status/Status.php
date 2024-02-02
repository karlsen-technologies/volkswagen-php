<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class Status
{
    public function __construct(
        public string $name,
        public ?string $carCapturedTimestamp,
        public array $values = [],
        public array $requests = [],
    ) {}

    public static function fromApi(string $name, array $data): Status
    {
        $values = $data['value'] ?? [];
        $carCapturedTimestamp = $values['carCapturedTimestamp'] ?? null;

        unset($values['carCapturedTimestamp']);

        $requests = $data['requests'] ?? [];

        foreach ($requests as $key => $requestData) {
            $requests[$key] = StatusRequest::fromApi($requestData);
        }

        return new Status(
            $name,
            $carCapturedTimestamp,
            $values,
            $requests,
        );
    }

    public static function fromArray(array $data): Status
    {
        $requests = [];

        foreach ($data['requests'] as $requestData) {
            $requests[] = StatusRequest::fromArray($requestData);
        }

        return new Status(
            $data['name'],
            $data['carCapturedTimestamp'],
            $data['values'],
            $requests,
        );
    }

    public function toArray(): array
    {
        $requests = [];

        foreach ($this->requests as $requestData) {
            $requests[] = $requestData->toArray();
        }

        return [
            'name' => $this->name,
            'carCapturedTimestamp' => $this->carCapturedTimestamp,
            'values' => $this->values,
            'requests' => $requests,
        ];
    }
}
