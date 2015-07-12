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

use ICanBoogie\Storage\FileStorage;

/**
 * Provides CLDR data from the filesystem.
 */
class FileProvider extends FileStorage implements Provider
{
	/**
	 * @inheritdoc
	 */
	protected function serialize($value)
	{
		return json_encode($value);
	}

	/**
	 * @inheritdoc
	 */
	protected function unserialize($value)
	{
		return json_decode($value, true);
	}

	/**
	 * @inheritdoc
	 */
	public function provide($path)
	{
		return $this->retrieve($path);
	}
}
