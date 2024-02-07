<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not Found', 404);
    }
}
