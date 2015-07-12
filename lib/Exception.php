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
 * CLDR exceptions implement this interface so that they can be easily recognized.
 *
 * <pre>
 * try
 * {
 *     // …
 * }
 * catch (\ICanBoogie\CLDR\Exception $e)
 * {
 *     // a CLDR exception
 * }
 * catch (\Exception $e
 * {
 *     // another type of exception
 * }
 * </pre>
 */
interface Exception
{

}
