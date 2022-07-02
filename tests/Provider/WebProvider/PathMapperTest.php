<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Provider\WebProvider;

use PHPUnit\Framework\TestCase;

final class PathMapperTest extends TestCase
{
	static private $sections = [

		'main' => [

			'dates' => 'dateFields timeZoneNames',
			'localenames' => 'languages localeDisplayNames scripts territories variants',
			'numbers' => 'currencies numbers',
			'misc' => 'characters contextTransforms delimiters layout listPatterns posix',
			'units' => 'measurementSystemNames units'

		],

		'segments' => [

			'segments' => 'suppressions'

		]

	];

	/**
	 * @var PathMapper
	 */
	private $mapper;

	protected function setUp(): void
	{
		$mapper = &$this->mapper;

		if (!$mapper)
		{
			$mapper = new PathMapper;
		}
	}

	/**
	 * @dataProvider provide_test_map
	 */
	public function test_map(string $path, string $expected): void
	{
		$origin = PathMapper::DEFAULT_ORIGIN;
		$version = PathMapper::DEFAULT_VERSION;
		$url = $this->mapper->map($path);

		$this->assertSame($origin . "/$version/cldr-json/cldr-$expected.json", $url);

		if (getenv('ICANBOOGIE_CLDR_CHECK_URL'))
		{
			$this->assertURLExists($url);
		}
	}

	/**
	 * @return array
	 */
	public function provide_test_map(): array
	{
		$variation = PathMapper::DEFAULT_VARIATION;
		$locale = 'fr';

		$cases = [

			[ 'availableLocales',          "core/availableLocales" ],
			[ 'defaultContent',            "core/defaultContent" ],
			[ 'scriptMetadata',            "core/scriptMetadata" ],
			[ 'supplemental/aliases',      "core/supplemental/aliases" ],
			[ 'supplemental/windowsZones', "core/supplemental/windowsZones" ],

			[ "main/$locale/ca-generic",   "dates-$variation/main/$locale/ca-generic" ],
			[ "main/$locale/ca-gregorian", "dates-$variation/main/$locale/ca-gregorian" ],
			[ "main/$locale/ca-japanese",  "cal-japanese-$variation/main/$locale/ca-japanese" ],

		];

		foreach (self::$sections as $section => $repositories)
		{
			foreach ($repositories as $repository => $files)
			{
				foreach (explode(' ', $files) as $file)
				{
					$cases[] = [ "$section/$locale/$file", "$repository-$variation/$section/$locale/$file" ];
				}
			}
		}

		return $cases;
	}

	private function assertURLExists(string $url): bool
	{
		static $ch;

		if (!$ch)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_NOBODY, true);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);

		return curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200;
	}
}
