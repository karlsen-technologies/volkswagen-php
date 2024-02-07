<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions\Identity;

use Exception;

class MissingParametersException extends Exception
{
    public function __construct()
    {
        parent::__construct('Missing parameters in the url');
    }
}
