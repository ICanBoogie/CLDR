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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedListFormatterTest extends TestCase
{
	/**
	 * @param array<scalar> $list
	 */
	#[DataProvider('provide_test_format')]
	public function test_format(array $list, ListType $type, string $locale_code, string $expected): void
	{
		$locale = get_repository()->locales[$locale_code];
		$this->assertNotNull($locale);
		$lp = new LocalizedListFormatter(new ListFormatter(), $locale);
		$this->assertSame($expected, $lp($list, $type));
	}

	public static function provide_test_format(): array
	{
		$sd = ListType::STANDARD;
		$st = ListType::UNIT_SHORT;

		return [

			[ [ ], $sd, 'en', "" ],
			[ [ 'one' ], $sd, 'en', "one" ],
			[ [ 'one', 'two' ], $sd, 'en', "one and two" ],
			[ [ 'one', 'two', 'three' ], $sd, 'en', "one, two, and three" ],
			[ [ 'one', 'two', 'three', 'four' ], $sd, 'en', "one, two, three, and four" ],

			[ [ ], $st, 'en', "" ],
			[ [ 'one' ], $st, 'en', "one" ],
			[ [ 'one', 'two' ], $st, 'en', "one, two" ],
			[ [ 'one', 'two', 'three' ], $st, 'en', "one, two, three" ],
			[ [ 'one', 'two', 'three', 'four' ], $st, 'en', "one, two, three, four" ],

			[ [ ], $sd, 'fr', "" ],
			[ [ 'un' ], $sd, 'fr', "un" ],
			[ [ 'un', 'deux' ], $sd, 'fr', "un et deux" ],
			[ [ 'un', 'deux', 'trois' ], $sd, 'fr', "un, deux et trois" ],
			[ [ 'un', 'deux', 'trois', 'quatre' ], $sd, 'fr', "un, deux, trois et quatre" ],

			[ [ ], $sd, 'de', "" ],
			[ [ 'eins' ], $sd, 'de', "eins" ],
			[ [ 'eins', 'zwei' ], $sd, 'de', "eins und zwei" ],
			[ [ 'eins', 'zwei', 'drei' ], $sd, 'de', "eins, zwei und drei" ],
			[ [ 'eins', 'zwei', 'drei', 'vier' ], $sd, 'de', "eins, zwei, drei und vier" ],

			[ [ 'January', 'February', 'March' ], ListType::STANDARD, 'en', "January, February, and March" ],
			[ [ 'Jan.', 'Feb.', 'Mar.' ], ListType::STANDARD_SHORT, 'en', "Jan., Feb., & Mar." ],
			[ [ 'Jan.', 'Feb.', 'Mar.' ], ListType::STANDARD_NARROW, 'en', "Jan., Feb., Mar." ],
			[ [ 'January', 'February', 'March' ], ListType::OR, 'en', "January, February, or March" ],
			[ [ 'Jan.', 'Feb.', 'Mar.' ], ListType::OR_SHORT, 'en', "Jan., Feb., or Mar." ],
			[ [ 'Jan.', 'Feb.', 'Mar.' ], ListType::OR_NARROW, 'en', "Jan., Feb., or Mar." ],
			[ [ '3 feet', '7 inches' ], ListType::UNIT, 'en', "3 feet, 7 inches" ],
			[ [ '3 ft', '7 in' ], ListType::UNIT_SHORT, 'en', "3 ft, 7 in" ],
			[ [ '3\'', '7"' ], ListType::UNIT_NARROW, 'en', "3' 7\"" ],

		];
	}
}
