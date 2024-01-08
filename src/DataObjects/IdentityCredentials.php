<?php

namespace kartech\Volkswagen\DataObjects;

class IdentityCredentials
{
    public function __construct(
        public string $state,
        public string $code,
        public string $accessToken,
        public string $expiresIn,
        public string $tokenType,
        public string $idToken,
    ) {
    }

    static function fromArray(array $data): IdentityCredentials
    {
        return new IdentityCredentials(
            $data['state'],
            $data['code'],
            $data['access_token'],
            $data['expires_in'],
            $data['token_type'],
            $data['id_token'],
        );
    }

    public function toArray(): array
    {
        return [
            'state' => $this->state,
            'code' => $this->code,
            'access_token' => $this->accessToken,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType,
            'id_token' => $this->idToken,
        ];
    }
}
