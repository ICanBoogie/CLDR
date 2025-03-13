<?php

namespace ICanBoogie\CLDR\Supplemental\Territory;

use ICanBoogie\CLDR\Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Exception thrown when a territory is not defined.
 */
final class TerritoryNotDefined extends InvalidArgumentException implements Exception
{
    /**
     * @param string $territory_code
     *     The ISO code of the territory.
     */
    public function __construct(
        public readonly string $territory_code,
        ?string $message = null,
        ?Throwable $previous = null
    ) {
        $message ??= "Territory not defined for code: $territory_code.";

        parent::__construct($message, 0, $previous);
    }
}
