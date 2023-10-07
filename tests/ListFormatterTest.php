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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ListFormatterTest extends TestCase
{
	#[DataProvider('provide_test_format')]
	public function test_format(array $list, ListPattern $list_pattern, string $expected): void
	{
		$formatter = new ListFormatter();
		$this->assertSame($expected, $formatter($list, $list_pattern));
	}

	public static function provide_test_format(): array
	{
		$lp1 = ListPattern::from([

            '2' =>  "{0} and {1}",
			'start' => "{0}, {1}",
            'middle' => "{0}, {1}",
            'end' => "{0}, and {1}",

		]);

		$lp2 = ListPattern::from([

			'2' =>  "{0} et {1}",
			'start' => "{0}, {1}",
			'middle' => "{0}, {1}",
			'end' => "{0} et {1}",

		]);

		return [

			[ [ ], $lp1, "" ],
			[ [ 'one' ], $lp1, "one" ],
			[ [ 'one', 'two' ], $lp1, "one and two" ],
			[ [ 'one', 'two', 'three' ], $lp1, "one, two, and three" ],
			[ [ 'one', 'two', 'three', 'four' ], $lp1, "one, two, three, and four" ],
			[ [ 'one', 'two', 'three', 'four', 'five' ], $lp1, "one, two, three, four, and five" ],

			[ [ ], $lp2, "" ],
			[ [ 'un' ], $lp2, "un" ],
			[ [ 'un', 'deux' ], $lp2, "un et deux" ],
			[ [ 'un', 'deux', 'trois' ], $lp2, "un, deux et trois" ],
			[ [ 'un', 'deux', 'trois', 'quatre' ], $lp2, "un, deux, trois et quatre" ],
			[ [ 'un', 'deux', 'trois', 'quatre', 'cinq' ], $lp2, "un, deux, trois, quatre et cinq" ]

		];
	}
}
