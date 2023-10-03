<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
	 *     The ISO code of the currency.
	 */
	public function __construct(
		public readonly string $currency_code,
		string $message = null,
		Throwable $previous = null
	) {
		$message ??= "Currency not defined for code: $currency_code.";

		parent::__construct($message, previous: $previous);
	}
}
