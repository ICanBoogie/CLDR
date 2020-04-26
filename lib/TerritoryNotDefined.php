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
use InvalidArgumentException;
use Throwable;

/**
 * Exception thrown when a territory is not defined.
 *
 * @property-read string $territory_code The ISO code of the territory.
 */
final class TerritoryNotDefined extends InvalidArgumentException implements Exception
{
	/**
	 * @uses get_territory_code
	 */
    use AccessorTrait;

    /**
     * @var string
     */
    private $territory_code;

    private function get_territory_code(): string
    {
        return $this->territory_code;
    }

    public function __construct(string $territory_code, string $message = null, Throwable $previous = null)
    {
        $this->territory_code = $territory_code;

        if (!$message)
        {
            $message = "Territory not defined for code: $territory_code.";
        }

        parent::__construct($message, 0, $previous);
    }
}
