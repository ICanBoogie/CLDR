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

class LocalizedLocaleTest extends TestCase
{
	/**
	 * @dataProvider provide_test_get_name
	 *
	 * @param string $locale_code
	 * @param string $code
	 * @param string $expected
	 */
	public function test_get_name($locale_code, $code, $expected)
	{
		$locale = new Locale(get_repository(), $code);
		$localized = new LocalizedLocale($locale, get_repository()->locales[$locale_code]);

		$this->assertEquals($expected, $localized->name);
	}

	public function provide_test_get_name()
	{
		return [

			[ 'fr', 'fr',    "français" ],
			[ 'fr', 'fr-CA', "français canadien" ],
			[ 'en', 'fr',    "French" ],
			[ 'en', 'fr-CA', "Canadian French" ],
			[ 'fr', 'nl',    "néerlandais" ],
			[ 'fr', 'nl-BE', "flamand" ],

		];
	}

	public function test_localize()
	{
		$locale = new Locale(get_repository(), 'fr');
		$localized = $locale->localize('es');
		$this->assertInstanceOf(LocalizedLocale::class, $localized);
		$this->assertEquals("francés", $localized->name);
	}
}
