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

use PHPUnit\Framework\TestCase;

final class LocalizedListFormatterTest extends TestCase
{
	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format(array $list, string $type, string $locale_code, string $expected): void
	{
		$lp = new LocalizedListFormatter(new ListFormatter, get_repository()->locales[$locale_code]);
		$this->assertSame($expected, $lp($list, $type));
	}

	public function provide_test_format(): array
	{
		$sd = LocalizedListFormatter::TYPE_STANDARD;
		$st = LocalizedListFormatter::TYPE_UNIT_SHORT;

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

		];
	}
}
