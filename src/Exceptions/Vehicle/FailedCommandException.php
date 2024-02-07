<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Vehicle;

use Exception;

class FailedCommandException extends Exception
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Failed to execute command');
    }
}
