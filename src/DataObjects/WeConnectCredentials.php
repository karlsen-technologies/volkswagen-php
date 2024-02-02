<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects;

class WeConnectCredentials
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public string $idToken,
    ) {}

    public static function fromArray(array $data): WeConnectCredentials
    {
        return new WeConnectCredentials(
            $data['access_token'] ?? $data['accessToken'],
            $data['refresh_token'] ?? $data['refreshToken'],
            $data['id_token'] ?? $data['idToken']
        );
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'id_token' => $this->idToken,
        ];
    }
}
