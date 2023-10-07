<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\GitHub;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UrlResolverTest extends TestCase
{
	#[DataProvider('provide_resolve')]
	public function test_resolve(string $path, string $expected): void
	{
		$sut = new UrlResolver();

		$this->assertSame($expected, $sut->resolve($path));
	}

	public static function provide_resolve(): array
	{
		$v = UrlResolver::DEFAULT_VERSION;

		return [

			"annotations-derived/{locale}/annotations" => [
				"annotations-derived/fr-CA/annotations",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-annotations-derived-modern/annotationsDerived/fr-CA/annotations.json"
			],

			"annotations/{locale}/annotations" => [
				"annotations/hi-Latn/annotations",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-annotations-modern/annotations/hi-Latn/annotations.json"
			],

			"bcp47/calendar" => [
				"bcp47/calendar",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-bcp47/bcp47/calendar.json"
			],

			"bcp47/transform_keyboard" => [
				"bcp47/transform_keyboard",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-bcp47/bcp47/transform_keyboard.json"
			],

			"cal-buddhist/{locale}/ca-buddhist" => [
				"cal-buddhist/de-BE/ca-buddhist",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-cal-buddhist-modern/main/de-BE/ca-buddhist.json"
			],

			"core/supplemental/plurals" => [
				"core/supplemental/plurals",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-core/supplemental/plurals.json"
			],

			"core/availableLocales" => [
				"core/availableLocales",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-core/availableLocales.json"
			],

			"core/defaultContent" => [
				"core/defaultContent",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-core/defaultContent.json"
			],

			"dates/{locale}/ca-gregorian" => [
				"dates/ko-KP/ca-gregorian",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-dates-modern/main/ko-KP/ca-gregorian.json"
			],

			"dates/{locale}/dateFields" => [
				"dates/ko-KP/dateFields",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-dates-modern/main/ko-KP/dateFields.json"
			],

			"localnames/{locale}/languages" => [
				"localenames/fr-MA/languages",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-localenames-modern/main/fr-MA/languages.json"
			],

			"misc/{locale}/listPatterns" => [
				"misc/ms-SG/listPatterns",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-misc-modern/main/ms-SG/listPatterns.json"
			],

			"numbers/{locale}/currencies" => [
				"numbers/en-150/currencies",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-numbers-modern/main/en-150/currencies.json"
			],

			"numbers/{locale}/numbers" => [
				"numbers/en-150/numbers",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-numbers-modern/main/en-150/numbers.json"
			],

			"rbnf/{language}" => [
				"rbnf/af",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-rbnf/rbnf/af.json"
			],

			"segments/{language}/suppressions" => [
				"segments/de/suppressions",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-segments-modern/segments/de/suppressions.json"
			],

			"units/{locale}/units" => [
				"units/pt-MO/units",
				"https://raw.githubusercontent.com/unicode-org/cldr-json/$v/cldr-json/cldr-units-modern/main/pt-MO/units.json"
			],

		];
	}
}
