<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Capability;

class Operation
{
    public function __construct(
        public string $id,
        public array $scopes
    ) {}

    public static function fromApi(array $data): Operation
    {
        return new Operation(
            $data['id'],
            $data['scopes']
        );
    }

    public static function fromArray(array $data): Operation
    {
        return new Operation(
            $data['id'],
            $data['scopes']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'scopes' => $this->scopes,
        ];
    }
}
