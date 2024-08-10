<?php

namespace ICanBoogie\CLDR;

use Throwable;

/**
 * CLDR exceptions implement this interface so that they can be easily recognized.
 *
 * ```php
 * try {
 *     // …
 * } catch (\ICanBoogie\CLDR\Exception $e) {
 *     // a CLDR exception
 * } catch (\Exception $e {
 *     // another type of exception
 * }
 * ```
 */
interface Exception extends Throwable
{
}
