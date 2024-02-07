<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class InvalidEmailException extends Exception
{
    public function __construct()
    {
        parent::__construct('Email address does not belong to a registered user');
    }
}
