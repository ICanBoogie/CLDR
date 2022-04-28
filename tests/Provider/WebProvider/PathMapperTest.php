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

class PathMapperTest extends \PHPUnit\Framework\TestCase
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

	public function setUp()
	{
		$mapper = &$this->mapper;

		if (!$mapper)
		{
			$mapper = new PathMapper;
		}
	}

	/**
	 * @dataProvider provide_test_map
	 *
	 * @param string $path
	 * @param string $expected
	 */
	public function test_map($path, $expected)
	{
		$origin = PathMapper::DEFAULT_ORIGIN;
		$url = $this->mapper->map($path);

		$this->assertSame($origin . "cldr-$expected.json", $url);

		if (getenv('ICANBOOGIE_CLDR_CHECK_URL'))
		{
			$this->assertURLExists($url);
		}
	}

	/**
	 * @return array
	 */
	public function provide_test_map()
	{
		$version = PathMapper::DEFAULT_VERSION;
		$variation = PathMapper::DEFAULT_VARIATION;
		$locale = 'fr';

		$cases = [

			[ 'availableLocales',          "core/$version/availableLocales" ],
			[ 'defaultContent',            "core/$version/defaultContent" ],
			[ 'scriptMetadata',            "core/$version/scriptMetadata" ],
			[ 'supplemental/aliases',      "core/$version/supplemental/aliases" ],
			[ 'supplemental/windowsZones', "core/$version/supplemental/windowsZones" ],

			[ "main/$locale/ca-generic",   "dates-$variation/$version/main/$locale/ca-generic" ],
			[ "main/$locale/ca-gregorian", "dates-$variation/$version/main/$locale/ca-gregorian" ],
			[ "main/$locale/ca-japanese",  "cal-japanese-$variation/$version/main/$locale/ca-japanese" ],

		];

		foreach (self::$sections as $section => $repositories)
		{
			foreach ($repositories as $repository => $files)
			{
				foreach (explode(' ', $files) as $file)
				{
					$cases[] = [ "$section/$locale/$file", "$repository-$variation/$version/$section/$locale/$file" ];
				}
			}
		}

		return $cases;
	}

	/**
	 * @param string $url
	 *
	 * @return bool
	 */
	private function assertURLExists($url)
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
