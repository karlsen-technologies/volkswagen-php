<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Api;

class AuthenticationForm
{
    public function __construct(
        public string $targetUrl,
        public array $parameters,
    ) {
    }

    public function toArray(): array
    {
        return [
            'target_url' => $this->targetUrl,
            'parameters' => $this->parameters,
        ];
    }
}
