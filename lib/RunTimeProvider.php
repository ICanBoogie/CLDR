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

use ICanBoogie\Storage\RunTimeStorage;

/**
 * Provides CLDR data from an array.
 */
class RunTimeProvider extends RunTimeStorage implements Provider
{
	use ProviderStorageBinding;
}
