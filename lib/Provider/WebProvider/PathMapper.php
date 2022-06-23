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

use function array_fill_keys;
use function array_shift;
use function array_unshift;
use function explode;
use function implode;
use function strpos;
use function substr;

/**
 * Maps CLDR paths to GitHub URLs.
 */
class PathMapper
{
	public const DEFAULT_ORIGIN = "https://raw.githubusercontent.com/unicode-cldr/";
	public const DEFAULT_VERSION = "36.0.0";
	public const DEFAULT_VARIATION = self::PREFER_MODERN;

	public const PREFER_MODERN = 'modern';
	public const PREFER_FULL = 'full';

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

	public function __construct(
		string $origin = self::DEFAULT_ORIGIN,
		string $version = self::DEFAULT_VERSION,
		string $variation = self::DEFAULT_VARIATION
	) {
		$this->origin = $origin;
		$this->version = $version;
		$this->variation = $variation;
		$this->files = $this->compile_files(self::$compact_files);
	}

	public function map(string $path): string
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

	private function map_localized(array $parts): array
	{
		$file = $parts[2];

		if (strpos($file, 'ca-') === 0)
		{
			$calendar = substr($file, 3);
			$repository = "cal-$calendar";

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

	private function compile_files(array $compact_files): array
	{
		$compiled = [];

		foreach ($compact_files as $name => $files)
		{
			$compiled += array_fill_keys(explode(' ', $files), $name);
		}

		return $compiled;
	}
}
