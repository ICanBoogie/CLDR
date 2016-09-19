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
	 * @var Repository
	 */
	private $repository;

	public function setUp()
	{
		$repository = &$this->repository;

		if (!$repository)
		{
			$repository = new Repository(create_provider_collection());
		}
	}

	/**
	 * @dataProvider provide_test_properties_instanceof
	 *
	 * @param string $property
	 * @param string $expected
	 */
	public function test_properties_instanceof($property, $expected)
	{
		$repository = $this->repository;
		$instance = $repository->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $repository->$property);
	}

	public function provide_test_properties_instanceof()
	{
		return [

			[ 'currencies',         CurrencyCollection::class ],
			[ 'locales',            LocaleCollection::class ],
			[ 'provider',           Provider::class ],
			[ 'supplemental',       Supplemental::class ],
			[ 'territories',        TerritoryCollection::class ],
			[ 'number_formatter',   NumberFormatter::class ],
			[ 'list_formatter',     ListFormatter::class ],
			[ 'plurals',            Plurals::class ],

		];
	}

	public function test_format_number()
	{
		$this->assertSame("4,123.37", $this->repository->format_number(4123.37, "#,#00.#0"));
	}
}
