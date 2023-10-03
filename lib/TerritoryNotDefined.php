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
		string $message = null,
		Throwable $previous = null
	) {
		$message ??= "Territory not defined for code: $territory_code.";

        parent::__construct($message, 0, $previous);
    }
}
