<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class InvalidPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct('Password is invalid');
    }
}
