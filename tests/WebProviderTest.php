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

class WebProviderTest extends \PHPUnit_Framework_TestCase
{
	public function test_provide_ok()
	{
		$provider = new WebProvider;

		$data = $provider->provide('main/fr/characters');
		$this->assertInternalType('array', $data);
		$this->assertArrayHasKey('main', $data);
	}

	/**
	 * @expectedException \ICanBoogie\CLDR\ResourceNotFound
	 */
	public function test_retrieve_failure()
	{
		$provider = new WebProvider;
		$path = 'undefined_locale/characters';

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
