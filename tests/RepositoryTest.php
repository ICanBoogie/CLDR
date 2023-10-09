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
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RepositoryTest extends TestCase
{
	private Repository $repository;

	protected function setUp(): void
	{
		$this->repository = get_repository();
	}

	#[DataProvider('provide_test_properties_instanceof')]
	public function test_properties_instanceof(string $property, string $expected): void
	{
		$repository = $this->repository;
		$instance = $repository->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $repository->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_properties_instanceof(): array
	{
		return [

			[ 'currencies', CurrencyCollection::class ],
			[ 'provider', Provider::class ],
			[ 'supplemental', Supplemental::class ],
			[ 'territories', TerritoryCollection::class ],
			[ 'number_formatter', NumberFormatter::class ],
			[ 'currency_formatter', CurrencyFormatter::class ],
			[ 'list_formatter', ListFormatter::class ],
			[ 'plurals', Plurals::class ],

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

			'2' => "{0} and {1}",
			'start' => "{0}, {1}",
			'middle' => "{0}, {1}",
			'end' => "{0}, and {1}",

		]);

		$this->assertSame("one, two, and three", $this->repository->format_list($list, $list_pattern));
	}
}
