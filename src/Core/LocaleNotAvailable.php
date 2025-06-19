<?php

namespace ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Exception thrown when a requested locale ID is not available.
 *
 * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/availableLocales.json
 */
final class LocaleNotAvailable extends InvalidArgumentException implements Exception
{
    /**
     * @param string $locale_id
     *     A locale ID.
     */
    public function __construct(
        public readonly string $locale_id,
        string $message = null,
        Throwable $previous = null
    ) {
        $message ??= "Locale ID is not available: $locale_id";

        parent::__construct($message, previous: $previous);
    }
}
