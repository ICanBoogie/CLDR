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

class RetrieverTest extends \PHPUnit_Framework_TestCase
{
	public function test_retrieve_ok()
	{
		$r = new Retriever;

		$json = $r('fr/characters');
		$this->assertStringStartsWith('{', $json);

		$data = json_decode($json, true);
		$this->assertArrayHasKey('main', $data);
	}

	/**
	 * @expectedException ICanBoogie\CLDR\ResourceNotFound
	 */
	public function test_retrieve_failure()
	{
		$r = new Retriever;
		$r('undefined_locale/characters');
	}
}