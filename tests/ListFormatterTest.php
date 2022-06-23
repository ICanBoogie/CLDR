<?php

namespace ICanBoogie\CLDR;

use LogicException;
use PHPUnit\Framework\TestCase;

class ListFormatterTest extends TestCase
{
	/**
	 * @dataProvider provide_test_format
	 *
	 * @param $list
	 * @param $list_patterns
	 * @param $expected
	 */
	public function test_format($list, $list_patterns, $expected)
	{
		$formatter = new ListFormatter;
		$this->assertSame($expected, $formatter($list, $list_patterns));
	}

	public function provide_test_format()
	{
		$lp1 = [

			'start' => "{0}, {1}",
            'middle' => "{0}, {1}",
            'end' => "{0}, and {1}",
            '2' =>  "{0} and {1}"

		];

		$lp2 = [

			'start' => "{0}, {1}",
			'middle' => "{0}, {1}",
			'end' => "{0} et {1}",
			'2' =>  "{0} et {1}"

		];

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

	public function test_localize()
	{
		$formatter = new ListFormatter(get_repository());
		$this->assertInstanceOf(LocalizedListFormatter::class, $formatter->localize('es'));
	}
}
