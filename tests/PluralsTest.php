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

/**
 * @group plurals
 */
class PluralsTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var Plurals
	 */
	private $plurals;

	public function setUp()
	{
		$plurals = &$this->plurals;

		if (!$plurals)
		{
			$plurals = new Plurals(get_repository()->supplemental['plurals']);
		}
	}

	/**
	 * @dataProvider provide_test_samples_for
	 *
	 * @param string $locale
	 * @param array $expected_keys
	 */
	public function test_samples_for($locale, array $expected_keys)
	{
		$samples = $this->plurals->samples_for($locale);

		$this->assertSame($expected_keys, array_keys($samples));
		$this->assertContainsOnlyInstancesOf(Samples::class, $samples);
	}

	public function provide_test_samples_for()
	{
		return [

			[ 'fr', [

				Plurals::COUNT_ONE,
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

	public function test_samples_should_be_the_same_for_the_same_locale()
	{
		$samples = $this->plurals->samples_for('fr');

		$this->assertSame($samples, $this->plurals->samples_for('fr'));
	}

	/**
	 * @dataProvider provide_test_rules_for
	 *
	 * @param string $locale
	 * @param array $expected_keys
	 */
	public function test_rules_for($locale, array $expected_keys)
	{
		$rules = $this->plurals->rules_for($locale);

		$this->assertSame($expected_keys, $rules);
	}

	public function provide_test_rules_for()
	{
		return [

			[ 'fr', [

				Plurals::COUNT_ONE,
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
	 * @param number $number
	 * @param string $locale
	 * @param string $expected
	 */
	public function test_rule_for($number, $locale, $expected)
	{
		$this->assertSame($expected, $this->plurals->rule_for($number, $locale));
	}

	public function provide_test_rule_for()
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
	 *
	 * @param string $locale
	 */
	public function test_rule_with_samples($locale)
	{
		$plurals = $this->plurals;
		$samples = $plurals->samples_for($locale);

		foreach ($samples as $expected => $sample)
		{
			foreach ($sample as $i => $number)
			{
				$count = $plurals->rule_for($number, $locale);

				try {
					$this->assertSame($expected, $count);
				} catch (\Exception $e) {
					$this->fail("Expected `$expected` but got `$count` for number `$number` ($locale, $i)");
				}
			}
		}
	}

	public function provide_test_rule_with_samples()
	{
		return [

			[ 'az' ],
			[ 'be' ],
			[ 'br' ],
			[ 'cy' ],
			[ 'fr' ],
			[ 'naq' ],

		];
	}
}
