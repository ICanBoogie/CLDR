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

use ICanBoogie\CLDR\Plurals\Samples;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @group plurals
 */
final class PluralsTest extends TestCase
{
	/**
	 * @var Plurals
	 */
	private $plurals;

	protected function setUp(): void
	{
		$plurals = &$this->plurals;

		if (!$plurals) // @phpstan-ignore-line
		{
			$plurals = new Plurals(get_repository()->supplemental['plurals']);
		}
	}

	/**
	 * @dataProvider provide_test_samples_for
	 */
	public function test_samples_for(string $locale, array $expected_keys): void
	{
		$samples = $this->plurals->samples_for($locale);

		$this->assertSame($expected_keys, array_keys($samples));
		$this->assertContainsOnlyInstancesOf(Samples::class, $samples);
	}

	public static function provide_test_samples_for(): array
	{
		return [

			[ 'fr', [

				Plurals::COUNT_ONE,
				Plurals::COUNT_MANY,
				Plurals::COUNT_OTHER

			] ],

			[ 'ar', [

				Plurals::COUNT_ZERO,
				Plurals::COUNT_ONE,
				Plurals::COUNT_TWO,
				Plurals::COUNT_FEW,
				Plurals::COUNT_MANY,
				Plurals::COUNT_OTHER

			] ],

			[ 'bs', [

				Plurals::COUNT_ONE,
				Plurals::COUNT_FEW,
				Plurals::COUNT_OTHER

			] ],

		];
	}

	public function test_samples_should_be_the_same_for_the_same_locale(): void
	{
		$samples = $this->plurals->samples_for('fr');

		$this->assertSame($samples, $this->plurals->samples_for('fr'));
	}

	/**
	 * @dataProvider provide_test_rules_for
	 */
	public function test_rules_for(string $locale, array $expected_keys): void
	{
		$rules = $this->plurals->rules_for($locale);

		$this->assertSame($expected_keys, $rules);
	}

	public static function provide_test_rules_for(): array
	{
		return [

			[ 'fr', [

				Plurals::COUNT_ONE,
				Plurals::COUNT_MANY,
				Plurals::COUNT_OTHER

			] ],

			[ 'ar', [

				Plurals::COUNT_ZERO,
				Plurals::COUNT_ONE,
				Plurals::COUNT_TWO,
				Plurals::COUNT_FEW,
				Plurals::COUNT_MANY,
				Plurals::COUNT_OTHER

			] ],

			[ 'bs', [

				Plurals::COUNT_ONE,
				Plurals::COUNT_FEW,
				Plurals::COUNT_OTHER

			] ],

		];
	}

	/**
	 * @dataProvider provide_test_rule_for
	 *
	 * @param numeric $number
	 */
	public function test_rule_for($number, string $locale, string $expected): void
	{
		$this->assertSame($expected, $this->plurals->rule_for($number, $locale));
	}

	public static function provide_test_rule_for(): array
	{
		return [

			[       0,       'ar', Plurals::COUNT_ZERO ],
			[       1,       'ar', Plurals::COUNT_ONE ],
			[       1.0,     'ar', Plurals::COUNT_ONE ],
			[      '1.0000', 'ar', Plurals::COUNT_ONE ],
			[       2,       'ar', Plurals::COUNT_TWO ],
			[      '2.0000', 'ar', Plurals::COUNT_TWO ],
			[       3,       'ar', Plurals::COUNT_FEW ],
			[      20,       'ar', Plurals::COUNT_MANY ],
			[  100000,       'ar', Plurals::COUNT_OTHER ],

		];
	}

	/**
	 * @dataProvider provide_test_rule_with_samples
	 */
	public function test_rule_with_samples(string $locale): void
	{
		$plurals = $this->plurals;
		$samples_per_count = $plurals->samples_for($locale);

		foreach ($samples_per_count as $expected => $samples)
		{
			foreach ($samples as $number)
			{
				$count = $plurals->rule_for($number, $locale);

				try {
					$this->assertSame($expected, $count);
				} catch (Throwable $e) {
					$this->fail("Expected `$expected` but got `$count` for number `$number` ($locale)");
				}
			}
		}
	}

	public static function provide_test_rule_with_samples(): array
	{
		return [

			[ 'az' ],
			[ 'be' ],
			[ 'br' ],
			[ 'cy' ],
			[ 'es' ],
			[ 'fr' ],
			[ 'naq' ],

		];
	}
}
