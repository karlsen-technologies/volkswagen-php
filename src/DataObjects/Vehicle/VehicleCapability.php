<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle;

class VehicleCapability
{
    public function __construct(
        public string $id,
        public string $expirationDate,
        public bool $userDisablingAllowed,
    ) {
    }

    static function fromArray(array $data): VehicleCapability
    {
        return new VehicleCapability(
            $data['id'],
            $data['expirationDate'],
            $data['userDisablingAllowed'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'expiration_date' => $this->expirationDate,
            'user_disabling_allowed' => $this->userDisablingAllowed,
        ];
    }
}
