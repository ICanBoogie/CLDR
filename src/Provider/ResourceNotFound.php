<?php

namespace ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Exception;

/**
 * Exception thrown in an attempt to read a path that doesn't exist on the CLDR source.
 */
final class ResourceNotFound extends \Exception implements Exception
{
}
