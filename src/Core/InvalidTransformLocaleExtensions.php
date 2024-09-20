<?php

namespace ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Exception;

class InvalidTransformLocaleExtensions extends \InvalidArgumentException implements Exception
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
