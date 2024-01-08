<?php

namespace kartech\Volkswagen\DataObjects\Api;

class AuthenticationRedirect
{
    public function __construct(
        public string $url
    ) {
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
        ];
    }
}
