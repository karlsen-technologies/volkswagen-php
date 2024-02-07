<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Capability;

class Parameter
{
    public function __construct(
        public string $key,
        public string $value
    ) {}

    public static function fromApi(array $data): Parameter
    {
        return new Parameter(
            $data['key'],
            $data['value']
        );
    }

    public static function fromArray(array $data): Parameter
    {
        return new Parameter(
            $data['key'],
            $data['value']
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
