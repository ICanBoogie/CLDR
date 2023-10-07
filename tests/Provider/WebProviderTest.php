<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\ResourceNotFound;
use PHPUnit\Framework\TestCase;

final class WebProviderTest extends TestCase
{
	public function test_provide_ok(): void
	{
		$provider = new WebProvider();
		$data = $provider->provide('misc/fr/characters');

		$this->assertIsArray($data);
		$this->assertArrayHasKey('main', $data);
	}

	public function test_provide_failure(): void
	{
		$this->expectException(ResourceNotFound::class);
		$provider = new WebProvider();
		$path = 'undefined_locale/characters';

		$this->expectException(ResourceNotFound::class);
		$provider->provide($path);
	}
}
