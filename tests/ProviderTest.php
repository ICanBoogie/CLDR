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

class ProviderTest extends \PHPUnit_Framework_TestCase
{
	static private $provider;

	static public function setupBeforeClass()
	{
		self::$provider = new Provider(new RunTimeCache(new FileCache(__DIR__ . '/repository')), new Retriever);
	}

	public function test_sections()
	{
		$data = self::$provider->fetch('fr/ca-gregorian');

		$this->assertInternalType('array', $data);
		$this->assertArrayHasKey('main', $data);
	}

	public function test_supplement()
	{
		$data = self::$provider->fetch('supplemental/calendarPreferenceData');
		$this->assertInternalType('array', $data);
		$this->assertArrayHasKey('supplemental', $data);
	}
}
