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
	static private $available_sections = [

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

	];

	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	/**
	 * Loaded sections.
	 *
	 * @var array
	 */
	protected $sections = [];

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

	/**
	 * @return CalendarCollection
	 */
	protected function lazy_get_calendars()
	{
		return new CalendarCollection($this);
	}

	/**
	 * @return Calendar
	 */
	protected function lazy_get_calendar()
	{
		return $this->calendars['gregorian']; // TODO-20131101: use preferred data
	}

	/**
	 * @return Numbers
	 */
	protected function lazy_get_numbers()
	{
		return new Numbers($this, $this['numbers']);
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

			$data = $this->repository->fetch("main/{$this->code}/{$offset}");
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
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	/**
	 * Localize the specified source.
	 *
	 * @param object $source
	 * @param array $options The options are passed to the localizer.
	 *
	 * @return mixed
	 */
	public function localize($source, array $options=[])
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
