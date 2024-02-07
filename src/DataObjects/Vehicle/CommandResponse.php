<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle;

class CommandResponse
{
    public function __construct(
        public array $data,
    ) {}

    public static function fromApi(array $data): CommandResponse
    {
        return new CommandResponse(
            $data['data'],
        );
    }

    public static function fromArray(array $data): CommandResponse
    {
        return new CommandResponse(
            $data['data']
        );
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
