<?php

namespace ICanBoogie\CLDR;

use InvalidArgumentException;
use Throwable;

/**
 * Exception thrown when a currency is not defined.
 */
class CurrencyNotDefined extends InvalidArgumentException implements Exception
{
	/**
	 * @param string $currency_code
	 *     A currency code; for example, EUR.
	 */
	public function __construct(
		public readonly string $currency_code,
		string $message = null,
		Throwable $previous = null
	) {
		$message ??= "Currency code is not defined: $currency_code";

		parent::__construct($message, previous: $previous);
	}
}
