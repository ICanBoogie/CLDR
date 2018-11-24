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

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Exception thrown when a currency is not defined.
 *
 * @property-read string $currency_code The ISO code of the currency.
 */
class CurrencyNotDefined extends \InvalidArgumentException implements Exception
{
	use AccessorTrait;

	/**
	 * @var string
	 */
	private $currency_code;

	/**
	 * @return string
	 */
	protected function get_currency_code()
	{
		return $this->currency_code;
	}

	/**
	 * @param string $currency_code
	 * @param null $message
	 * @param \Exception|null $previous
	 */
	public function __construct($currency_code, $message = null, \Exception $previous = null)
	{
		$this->currency_code = $currency_code;

		if (!$message)
		{
			$message = "Currency not defined for code: $currency_code.";
		}

		parent::__construct($message, 0, $previous);
	}
}
