<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Capability;

class Capability
{
    public function __construct(
        public string $id,
        public string $expirationDate,
        public bool $userDisablingAllowed,
        public string $endpoint,
        public bool $isEnabled,
        public array $status,
        public array $operations,
        public array $parameters,
    ) {}

    public static function fromApi(array $data): Capability
    {
        $operations = [];

        foreach ($data['operations'] as $operation) {
            $operations[] = Operation::fromApi($operation);
        }

        $parameters = [];

        foreach ($data['parameters'] as $parameter) {
            $parameters[] = Parameter::fromApi($parameter);
        }

        return new Capability(
            $data['id'],
            $data['expirationDate'],
            $data['userDisablingAllowed'],
            $data['endpoint'],
            $data['isEnabled'],
            $data['status'],
            $operations,
            $parameters,
        );
    }

    public static function fromArray(array $data): Capability
    {
        $operations = [];

        foreach ($data['operations'] as $operation) {
            $operations[] = Operation::fromArray($operation);
        }

        $parameters = [];

        foreach ($data['parameters'] as $parameter) {
            $parameters[] = Parameter::fromArray($parameter);
        }

        return new Capability(
            $data['id'],
            $data['expiration_date'],
            $data['user_disabling_allowed'],
            $data['endpoint'],
            $data['is_enabled'],
            $data['status'],
            $operations,
            $parameters,
        );
    }

    public function toArray(): array
    {
        $operations = [];

        foreach ($this->operations as $operation) {
            $operations[] = $operation->toArray();
        }

        $parameters = [];

        foreach ($this->parameters as $parameter) {
            $parameters[] = $parameter->toArray();
        }

        return [
            'id' => $this->id,
            'expiration_date' => $this->expirationDate,
            'user_disabling_allowed' => $this->userDisablingAllowed,
            'endpoint' => $this->endpoint,
            'is_enabled' => $this->isEnabled,
            'status' => $this->status,
            'operations' => $operations,
            'parameters' => $parameters,
        ];
    }
}
