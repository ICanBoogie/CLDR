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

interface Cache
{
	/**
	 * @return array<string, mixed>|null
	 */
	public function get(string $path): ?array;

	/**
	 * @param array<string, mixed> $data
	 */
	public function set(string $path, array $data): void;
}
