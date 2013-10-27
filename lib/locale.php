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

use ICanBoogie\OffsetNotWritable;
use ICanBoogie\OffsetNotDefined;
use ICanBoogie\PropertyNotDefined;

/**
 * Representation of a locale.
 *
 * @property-read Repository $repository The repository provided during construct.
 * @property-read string $identity The identity of the locale.
 * @property-read CalendarCollection $calendars The calendar collection of the locale.
 */
class Locale implements \ArrayAccess
{
	static private $available_sections = array
	(
		'ca-buddhist'            => 'dates/calendars/buddhist',
		'ca-chinese'             => 'dates/calendars/chinese',
		'ca-coptic'              => 'dates/calendars/coptic',
		'ca-dangi'               => 'dates/calendars/dangi',
		'ca-ethiopic-amete-alem' => 'dates/calendars/ethiopic-amete-alem',
		'ca-ethiopic'            => 'dates/calendars/ethiopic',
		'ca-generic'             => 'dates/calendars/generic',
		'ca-gregorian'           => 'dates/calendars/gregorian',
		'ca-hebrew'              => 'dates/calendars/hebrew',
		'ca-indian'              => 'dates/calendars/indian',
		'ca-islamic-civil'       => 'dates/calendars/islamic-civil',
		'ca-islamic-rgsa'        => 'dates/calendars/islamic-rgsa',
		'ca-islamic-tbla'        => 'dates/calendars/islamic-tbla',
		'ca-islamic-umalqura'    => 'dates/calendars/islamic-umalqura',
		'ca-islamic'             => 'dates/calendars/islamic',
		'ca-japanese'            => 'dates/calendars/japanese',
		'ca-persian'             => 'dates/calendars/persian',
		'ca-roc'                 => 'dates/calendars/roc',
		'characters'             => 'characters',
		'contextTransforms'      => 'contextTransforms',
		'currencies'             => 'numbers/currencies',
		'dateFields'             => 'dates/fields',
		'delimiters'             => 'delimiters',
		'languages'              => 'localeDisplayNames/languages',
		'layout'                 => 'layout',
		'listPatterns'           => 'listPatterns',
		'localeDisplayNames'     => 'localeDisplayNames',
		'measurementSystemNames' => 'localeDisplayNames/measurementSystemNames',
		'numbers'                => 'numbers',
		'posix'                  => 'posix',
		'scripts'                => 'localeDisplayNames/scripts',
		'territories'            => 'localeDisplayNames/territories',
		'timeZoneNames'          => 'dates/timeZoneNames',
		'transformNames'         => 'localeDisplayNames/transformNames',
		'units'                  => 'units',
		'variants'               => 'localeDisplayNames/variants'
	);

	/**
	 * Representation of a CLDR.
	 *
	 * @var Repository
	 */
	protected $repository;

	/**
	 * CLDR identity.
	 *
	 * @var string
	 */
	protected $identity;

	/**
	 * Loaded sections.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * Collection of calendars.
	 *
	 * @var CalendarCollection
	 */
	protected $calendars;

	/**
	 * Initializes the {@link $repository} and {@link $identity} properties.
	 *
	 * @param Repository $repository
	 * @param string $identity Locale identifier.
	 */
	public function __construct(Repository $repository, $identity)
	{
		$this->repository = $repository;
		$this->identity = $identity;
	}

	public function __get($property)
	{
		switch ($property)
		{
			case 'repository': return $this->get_repository();
			case 'identity':   return $this->get_identity();
			case 'calendars':  return $this->get_calendars();
		}

		throw new PropertyNotDefined(array($property, $this));
	}

	protected function get_repository()
	{
		return $this->repository;
	}

	protected function get_identity()
	{
		return $this->identity;
	}

	protected function get_calendars()
	{
		if ($this->calendars)
		{
			return $this->calendars;
		}

		return $this->calendars = new CalendarCollection($this);
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
				throw new OffsetNotDefined(array($offset, $this));
			}

			$data = $this->repository->provider->fetch("{$this->identity}/{$offset}");
			$path = "main/{$this->identity}/" . self::$available_sections[$offset];
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
		throw new OffsetNotWritable(array($offset, $this));
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable(array($offset, $this));
	}
}