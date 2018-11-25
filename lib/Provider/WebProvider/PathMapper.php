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

/**
 * Maps CLDR paths to GitHub URLs.
 */
class PathMapper
{
	const DEFAULT_ORIGIN = "https://raw.githubusercontent.com/unicode-cldr/";
	const DEFAULT_VERSION = "34.0.0";
	const DEFAULT_VARIATION = self::PREFER_MODERN;

	const PREFER_MODERN = 'modern';
	const PREFER_FULL = 'full';

	static private $compact_files = [

		'dates' => 'dateFields timeZoneNames',
		'localenames' => 'languages localeDisplayNames scripts territories variants',
		'numbers' => 'currencies numbers',
		'misc' => 'characters contextTransforms delimiters layout listPatterns posix',
		'units' => 'measurementSystemNames units',
		'segments' => 'suppressions'

	];

	/**
	 * @var string
	 */
	private $origin;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $variation;

	/**
	 * @var array
	 */
	private $files;

	/**
	 * @param string $origin
	 * @param string $version
	 * @param string $variation
	 */
	public function __construct(
		$origin = self::DEFAULT_ORIGIN,
		$version = self::DEFAULT_VERSION,
		$variation = self::DEFAULT_VARIATION
	) {
		$this->origin = $origin;
		$this->version = $version;
		$this->variation = $variation;
		$this->files = $this->compile_files(self::$compact_files);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function map($path)
	{
		$parts = explode('/', $path);

		switch ($parts[0])
		{
			case 'main':
			case 'segments':
				$parts = $this->map_localized($parts);
				break;

			default:
				array_unshift($parts, 'core');
		}

		$p0 = array_shift($parts);
		array_unshift($parts, $p0, $this->version);

		$path = implode('/', $parts);

		return "{$this->origin}cldr-$path.json";
	}

	/**
	 * @param array $parts
	 *
	 * @return array
	 */
	private function map_localized(array $parts)
	{
		$file = $parts[2];

		if (strpos($file, 'ca-') === 0)
		{
			$calendar = substr($file, 3);
			$repository = "cal-{$calendar}";

			if ($calendar === 'generic' || $calendar === 'gregorian')
			{
				$repository = "dates";
			}
		}
		else
		{
			$repository = $this->files[$file];
		}

		array_unshift($parts, "$repository-{$this->variation}");

		return $parts;
	}

	/**
	 * @param array $compact_files
	 *
	 * @return array
	 */
	private function compile_files(array $compact_files)
	{
		$compiled = [];

		foreach ($compact_files as $name => $files)
		{
			$compiled += array_fill_keys(explode(' ', $files), $name);
		}

		return $compiled;
	}
}
