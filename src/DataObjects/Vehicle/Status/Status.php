<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class Status
{
    public function __construct(
        public string $name,
        public ?string $carCapturedTimestamp,
        public array $values = [],
    ) {
    }

    static function fromApi(string $name, array $data): Status
    {
        $values = $data['value'] ?? [];
        $carCapturedTimestamp = $values['carCapturedTimestamp'] ?? null;

        unset($values['carCapturedTimestamp']);

        return new Status(
            $name,
            $carCapturedTimestamp,
            $values,
        );
    }

    static function fromArray(array $data): Status
    {
        return new Status(
            $data['name'],
            $data['carCapturedTimestamp'],
            $data['values'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'carCapturedTimestamp' => $this->carCapturedTimestamp,
            'values' => $this->values,
        ];
    }
}
