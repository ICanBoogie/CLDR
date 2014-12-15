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
 * Exception thrown when a data does not exists for a territory.
 *
 * @package ICanBoogie\CLDR
 */
class NoTerritoryData extends \LogicException
{
	public function __construct($message="Territory data is missing.", $code=500, \Exception $previous=null)
	{
		parent::__construct($message, $code, $previous);
	}
}
