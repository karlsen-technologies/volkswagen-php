<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class InvalidFormException extends Exception
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'Failed to find a valid form in the response body');
    }
}
