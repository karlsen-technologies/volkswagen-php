<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    use hasJsonErrorMessage;

    public function __construct(string $body)
    {
        parent::__construct($this->message($body) ?? 'Unauthorized', 401);
    }
}
