<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class StartAuthorizationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Failed to start the authorization process');
    }
}
