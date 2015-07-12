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

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provide_test_properties_instanceof
	 */
	public function test_properties_instanceof($property, $expected)
	{
		$repository = new Repository(create_provider_collection());
		$instance = $repository->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $repository->$property);
	}

	public function provide_test_properties_instanceof()
	{
		return [

			[ 'currencies',         'ICanBoogie\CLDR\CurrencyCollection' ],
			[ 'locales',            'ICanBoogie\CLDR\LocaleCollection' ],
			[ 'provider',           'ICanBoogie\CLDR\Provider' ],
			[ 'supplemental',       'ICanBoogie\CLDR\Supplemental' ],
			[ 'territories',        'ICanBoogie\CLDR\TerritoryCollection' ],
			[ 'number_formatter',   'ICanBoogie\CLDR\NumberFormatter' ],
			[ 'list_formatter',     'ICanBoogie\CLDR\ListFormatter' ],

		];
	}
}
