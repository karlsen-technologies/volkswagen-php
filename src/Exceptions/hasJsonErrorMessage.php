<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions;

trait hasJsonErrorMessage
{
    public function message(string $body): ?string
    {
        // Attempt to decode the response body
        $decoded = json_decode($body, true);

        // If the response body is JSON and contains an error message, use that
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['error'])) {
            return $decoded['error']['info'];
        }

        return null;
    }
}
