<?php

namespace ICanBoogie\CLDR;

class LocalizedListFormatterTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_format
	 *
	 * @param $list
	 * @param $list_patterns_ot_type
	 * @param $locale_code
	 * @param $expected
	 */
	public function test_format($list, $list_patterns_ot_type, $locale_code, $expected)
	{
		$lp = new LocalizedListFormatter(new ListFormatter, get_repository()->locales[$locale_code]);
		$this->assertSame($expected, $lp($list, $list_patterns_ot_type));
	}

	public function provide_test_format()
	{
		$lp = [

			'start' => "{0}, {1}",
            'middle' => "{0}, {1}",
            'end' => "{0}, and {1}",
            '2' =>  "{0} and {1}"

		];

		$sd = LocalizedListFormatter::TYPE_STANDARD;
		$st = LocalizedListFormatter::TYPE_UNIT_SHORT;

		return [

			[ [ ], $lp, 'en', "" ],
			[ [ 'one' ], $lp, 'en', "one" ],
			[ [ 'one', 'two' ], $lp, 'en', "one and two" ],
			[ [ 'one', 'two', 'three' ], $lp, 'en', "one, two, and three" ],
			[ [ 'one', 'two', 'three', 'four' ], $lp, 'en', "one, two, three, and four" ],

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
			[ [ 'un' ], $sd, 'de', "un" ],
			[ [ 'un', 'deux' ], $sd, 'de', "un und deux" ],
			[ [ 'un', 'deux', 'trois' ], $sd, 'de', "un, deux und trois" ],
			[ [ 'un', 'deux', 'trois', 'quatre' ], $sd, 'de', "un, deux, trois und quatre" ],

		];
	}
}
