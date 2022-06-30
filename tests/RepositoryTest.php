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

use ICanBoogie\CLDR\Locale\ListPattern;
use PHPUnit\Framework\TestCase;

final class RepositoryTest extends TestCase
{
	/**
	 * @var Repository
	 */
	private $repository;

	protected function setUp(): void
	{
		$this->repository = get_repository();
	}

	/**
	 * @dataProvider provide_test_properties_instanceof
	 */
	public function test_properties_instanceof(string $property, string $expected): void
	{
		$repository = $this->repository;
		$instance = $repository->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $repository->$property);
	}

	public function provide_test_properties_instanceof(): array
	{
		return [

			[ 'currencies',         CurrencyCollection::class ],
			[ 'locales',            LocaleCollection::class ],
			[ 'provider',           Provider::class ],
			[ 'supplemental',       Supplemental::class ],
			[ 'territories',        TerritoryCollection::class ],
			[ 'number_formatter',   NumberFormatter::class ],
			[ 'currency_formatter', CurrencyFormatter::class ],
			[ 'list_formatter',     ListFormatter::class ],
			[ 'plurals',            Plurals::class ],

		];
	}

	public function test_format_number(): void
	{
		$this->assertSame(
			"4,123.37",
			$this->repository->format_number(4123.37, "#,#00.#0")
		);
	}

	public function test_format_currency(): void
	{
		$this->assertSame(
			"$4,123.37",
			$this->repository->format_currency(4123.37, "¤#,#00.#0", null, '$')
		);
	}

	public function test_format_list(): void
	{
		$list = [ 'one', 'two', 'three' ];
		$list_pattern = ListPattern::from([

			'2' =>  "{0} and {1}",
			'start' => "{0}, {1}",
			'middle' => "{0}, {1}",
			'end' => "{0}, and {1}",

		]);

		$this->assertSame("one, two, and three", $this->repository->format_list($list, $list_pattern));
	}

	/**
	 * @dataProvider provide_test_properties
	 */
	public function test_properties(string $property, callable $assert): void
	{
		$assert($this->repository->$property);
	}

	public function provide_test_properties(): array
	{
		return [

			[ 'available_locales', function($value) {

				$this->assertContains('fr', $value);
				$this->assertContains('en', $value);
				$this->assertNotContains('fr-FR', $value);
				$this->assertNotContains('en-US', $value);

			} ]

		];
	}

	/**
	 * @dataProvider provide_test_is_locale_available
	 */
	public function test_is_locale_available(string $locale, bool $expected): void
	{
		$this->assertSame($expected, get_repository()->is_locale_available($locale));
	}

	public function provide_test_is_locale_available(): array
	{
		return [

			[ 'fr', true ],
			[ 'en', true ],
			[ 'fr-FR', false ],
			[ 'en-US', false ],

		];
	}
}
