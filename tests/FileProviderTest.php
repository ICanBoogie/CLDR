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

class FileProviderTest extends \PHPUnit_Framework_TestCase
{
	static private $directory;

	static public function setupBeforeClass()
	{
		self::$directory = __DIR__ . DIRECTORY_SEPARATOR . 'repository' . DIRECTORY_SEPARATOR;
	}

	public function test_provider()
	{
		$path = 'some/path';
		$root = self::$directory;

		if (file_exists($root . 'some--path'))
		{
			unlink($root . 'some--path');
		}

		$expected = array('from http' => true );

		$stub = $this->getMockBuilder('ICanBoogie\CLDR\WebProvider')
			->getMock();

		$stub
			->expects($this->once())
			->method('provide')
			->willReturn($expected);

		$provider = new FileProvider($stub, $root);

		for ($i = 0 ; $i < 5 ; $i++)
		{
			$data = $provider->provide($path);
			$this->assertSame($expected, $data);
			$this->assertTrue($provider->exists($path));
		}
	}
}
