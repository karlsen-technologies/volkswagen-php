<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class LoginThrottledException extends Exception
{
    public function __construct()
    {
        parent::__construct('User account has been throttled');
    }
}
