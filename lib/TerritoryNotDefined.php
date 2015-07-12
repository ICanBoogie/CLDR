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
 * Exception thrown when a territory is not defined.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read string $territory_code The ISO code of the territory.
 */
class TerritoryNotDefined extends \InvalidArgumentException implements Exception
{
    use AccessorTrait;

    private $territory_code;

    protected function get_territory_code()
    {
        return $this->territory_code;
    }

    public function __construct($territory_code, $message = null, $code = 500, \Exception $previous = null)
    {
        $this->territory_code = $territory_code;

        if (!$message)
        {
            $message = "Territory not defined for code: $territory_code.";
        }

        parent::__construct($message, $code, $previous);
    }
}
