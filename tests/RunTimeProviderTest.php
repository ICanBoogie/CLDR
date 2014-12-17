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

class RunTimeProviderTest extends \PHPUnit_Framework_TestCase
{
	public function test_provider()
	{
		$path = 'some/path';
		$expected = array('from http' => true );

		$stub = $this->getMockBuilder('ICanBoogie\CLDR\WebProvider')
			->getMock();

		$stub
			->expects($this->once())
			->method('provide')
			->willReturn($expected);

		$provider = new RunTimeProvider($stub);

		for ($i = 0 ; $i < 5 ; $i++)
		{
			$data = $provider->provide($path);
			$this->assertSame($expected, $data);
			$this->assertTrue($provider->exists($path));
		}
	}
}
