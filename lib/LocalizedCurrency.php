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

/**
 * Localized currency.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read string $name The localized name of the currency.
 * @property-read string $symbol The localized symbol of the currency.
 */
class LocalizedCurrency extends LocalizedObject
{
	/**
	 * Returns the formatter to use to format the target object.
	 *
	 * @return mixed
	 */
	protected function get_formatter()
	{
		// TODO: Return a CurrencyFormatter instance.
	}

	/**
	 * The localized name of the currency.
	 *
	 * @param int|null $count Used for pluralization.
	 *
	 * @return string
	 */
	public function get_name($count = null)
	{
		$offset = 'displayName';

		if ($count == 1)
		{
			$offset .= '-count-one';
		}
		else if ($count)
		{
			$offset .= '-count-other';
		}

		return $this->locale['currencies'][$this->target->code][$offset];
	}

	/**
	 * Return the localized symbol of the currency.
	 *
	 * @return string
	 */
	protected function get_symbol()
	{
		return $this->locale['currencies'][$this->target->code]['symbol'];
	}
}
