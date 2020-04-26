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

class WebProviderTest extends TestCase
{
	/**
	 * @throws ResourceNotFound
	 */
	public function test_provide_ok()
	{
		$provider = new WebProvider;

		$data = $provider->provide('main/fr/characters');
		$this->assertIsArray($data);
		$this->assertArrayHasKey('main', $data);
	}

	public function test_retrieve_failure()
	{
		$this->expectException(ResourceNotFound::class);
		$provider = new WebProvider;
		$path = 'undefined_locale/characters';

		$this->expectException(ResourceNotFound::class);

		try
		{
			$provider->provide($path);
		}
		catch (ResourceNotFound $e)
		{
			$this->assertEquals($path, $e->path);

			throw $e;
		}
	}
}
