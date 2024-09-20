<?php

namespace ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Exception;

class InvalidLocaleId extends \InvalidArgumentException implements Exception
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
