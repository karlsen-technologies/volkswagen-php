<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

use KarlsenTechnologies\Volkswagen\Enums\Vehicle\StatusDomain;

class Domain
{
    public function __construct(
        public ?StatusDomain $type,
        public string $rawType,
        public array $statuses = [],
    ) {
    }

    static function fromApi(string $name, array $data): Domain
    {
        $statuses = [];

        foreach($data as $statusName => $statusData) {
            $statuses[$statusName] = Status::fromApi($statusName, $statusData);
        }

        return new Domain(
            StatusDomain::tryFrom($name),
            $name,
            $statuses,
        );
    }

    static function fromArray(array $data): Domain
    {
        $statuses = [];

        foreach($data['statuses'] as $statusName => $statusData) {
            $statuses[$statusName] = Status::fromArray($statusData);
        }

        return new Domain(
            StatusDomain::tryFrom($data['type']),
            $data['raw_type'],
            $statuses,
        );
    }

    public function toArray(): array
    {
        $statuses = [];

        foreach($this->statuses as $statusName => $status) {
            $statuses[$statusName] = $status->toArray();
        }

        return [
            'type' => $this->type?->value,
            'raw_type' => $this->rawType,
            'statuses' => $statuses,
        ];
    }
}
