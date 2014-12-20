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

use ICanBoogie\OffsetNotDefined;
use ICanBoogie\OffsetNotWritable;

/**
 * Representation of the "supplemental" section.
 *
 * <pre>
 * <?php
 *
 * use ICanBoogie\CLDR\Supplemental;
 *
 * $supplemental = new Supplemental($repository);
 *
 * echo $supplemental['calendarPreferenceData']['001']; // gregorian
 * </pre>
 */
class Supplemental implements \ArrayAccess
{
	static private $available_sections = [

		'calendarData'           => 'calendarData',
		'calendarPreferenceData' => 'calendarPreferenceData',
		'characterFallbacks'     => 'characters/character-fallback',
		'codeMappings'           => 'codeMappings',
		'currencyData'           => 'currencyData',
		'dayPeriods'             => 'dayPeriodRuleSet',
		'gender'                 => 'gender',
		'languageData'           => 'languageData',
		'languageMatching'       => 'languageMatching',
		'likelySubtags'          => 'likelySubtags',
		'measurementData'        => 'measurementData',
		'metaZones'              => 'metaZones',
		'numberingSystems'       => 'numberingSystems',
		'ordinals'               => 'plurals-type-ordinal',
		'parentLocales'          => 'parentLocales',
		'plurals'                => 'plurals-type-cardinal',
		'postalCodeData'         => 'postalCodeData',
		'primaryZones'           => 'primaryZones',
		'references'             => 'references',
		'telephoneCodeData'      => 'telephoneCodeData',
		'territoryContainment'   => 'territoryContainment',
		'territoryInfo'          => 'territoryInfo',
		'timeData'               => 'timeData',
		'weekData'               => 'weekData',
		'windowsZones'           => 'windowsZones'

	];

	use RepositoryPropertyTrait;

	/**
	 * Loaded sections.
	 *
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Initializes the {@link $repository} property.
	 *
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}

	public function offsetExists($offset)
	{
		return isset(self::$available_sections[$offset]);
	}

	public function offsetGet($offset)
	{
		if (empty($this->sections[$offset]))
		{
			if (empty(self::$available_sections[$offset]))
			{
				throw new OffsetNotDefined([ $offset, $this ]);
			}

			$data = $this->repository->fetch("supplemental/{$offset}");
			$path = 'supplemental/' . self::$available_sections[$offset];
			$path_parts = explode('/', $path);

			foreach ($path_parts as $part)
			{
				$data = $data[$part];
			}

			$this->sections[$offset] = $data;
		}

		return $this->sections[$offset];
	}

	public function offsetSet($offset, $value)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}
}
