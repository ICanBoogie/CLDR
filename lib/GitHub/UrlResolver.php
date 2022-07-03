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

use function explode;
use function in_array;

/**
 * Resolves a relative CLDR path into a (raw) GitHub URL.
 */
class UrlResolver
{
	public const DEFAULT_ORIGIN = "https://raw.githubusercontent.com/unicode-org/cldr-json/";
	public const DEFAULT_VERSION = "41.0.0";
	public const DEFAULT_VARIATION = self::VARIATION_MODERN;

	public const VARIATION_MODERN = 'modern';
	public const VARIATION_FULL = 'full';

	/**
	 * List of sections that don't have variations, that is no '-full' or '-modern' suffix.
	 */
	private const INVARIANT = [ 'bcp47', 'core', 'rbnf' ];

	private const MAIN_OVERRIDE = [

		"annotations-derived" => "annotationsDerived",
		"annotations" => "annotations",
		"bcp47" => "bcp47",
		"core" => "", // no _main_, files are available at the base
		"rbnf" => "rbnf",
		"segments" => "segments",

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

	public function __construct(
		string $origin = self::DEFAULT_ORIGIN,
		string $version = self::DEFAULT_VERSION,
		string $variation = self::DEFAULT_VARIATION
	)
	{
		$this->origin = $origin;
		$this->version = $version;
		$this->variation = $variation;
	}

	/**
	 * @param string $path A relative path e.g. 'numbers/en-150/currencies'
	 *
	 * @return string Resolved URL.
	 */
	public function resolve(string $path): string
	{
		$parts = explode("/", $path, 2);
		$p0 = array_shift($parts);
		$p9 = array_shift($parts);
		$main = self::MAIN_OVERRIDE[$p0] ?? "main";

		if ($main)
		{
			$main .= "/";
		}

		if (!in_array($p0, self::INVARIANT))
		{
			$p0 = "$p0-$this->variation";
		}

		return "$this->origin$this->version/cldr-json/cldr-$p0/$main$p9.json";
	}
}
