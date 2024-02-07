<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class SubmitPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct('Failed to submit password');
    }
}
