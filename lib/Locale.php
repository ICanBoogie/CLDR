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
 * @property-read string $code The ISO code of the locale.
 * @property-read CalendarCollection $calendars The calendar collection of the locale.
 * @property-read Calendar $calendar The preferred calendar for this locale.
 * @property-read Numbers $numbers
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
	 * @var string
	 */
	protected $code;

	/**
	 * Loaded sections.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * Initializes the {@link $repository} and {@link $code} properties.
	 *
	 * @param Repository $repository
	 * @param string $code The ISO code of the locale.
	 */
	public function __construct(Repository $repository, $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	public function __get($property)
	{
		switch ($property)
		{
			case 'repository': return $this->get_repository();
			case 'code':       return $this->get_code();
			case 'calendars':  return $this->get_calendars();
			case 'calendar':   return $this->get_calendar();
			case 'numbers':    return $this->get_numbers();
		}

		throw new PropertyNotDefined(array($property, $this));
	}

	public function __toString()
	{
		return $this->code;
	}

	protected function get_repository()
	{
		return $this->repository;
	}

	protected function get_code()
	{
		return $this->code;
	}

	/**
	 * Collection of calendars.
	 *
	 * @var CalendarCollection
	 */
	private $calendars;

	/**
	 * Returns the calendars available for this locale.
	 *
	 * @return CalendarCollection
	 */
	protected function get_calendars()
	{
		if ($this->calendars)
		{
			return $this->calendars;
		}

		return $this->calendars = new CalendarCollection($this);
	}

	private $calendar;

	protected function get_calendar()
	{
		if ($this->calendar)
		{
			return $this->calendar;
		}

		return $this->calendar = $this->get_calendars()->offsetGet('gregorian'); // TODO-20131101: use preferred data
	}

	/**
	 * @var Numbers
	 */
	private $numbers;

	protected function get_numbers()
	{
		if (!$this->numbers)
		{
			$this->numbers = new Numbers($this, $this['numbers']);
		}

		return $this->numbers;
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

			$data = $this->repository->provider->fetch("main/{$this->code}/{$offset}");
			$path = "main/{$this->code}/" . self::$available_sections[$offset];
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

	/**
	 * Localize the specified source.
	 *
	 * @param object $source
	 * @param array $options The options are passed to the localizer.
	 *
	 * @return mixed
	 */
	public function localize($source, array $options=array())
	{
		$constructor = $this->resolve_localize_constructor($source);

		if ($constructor)
		{
			return call_user_func($constructor, $source, $this, $options);
		}

		throw new \LogicException("Unable to localize source");
	}

	private function resolve_localize_constructor($source)
	{
		$class = get_class($source);

		if ($source instanceof LocalizeAwareInterface)
		{
			return $class . '::localize';
		}

		$base = basename(strtr($class, '\\', '/'));
		$constructor = 'ICanBoogie\CLDR\Localized' . $base;

		if (!class_exists($constructor))
		{
			return;
		}

		return $constructor . '::from';
	}
}
