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

/**
 * Representation of a locale calendar.
 *
 * @property-read Locale $locale The locale this calendar is defined in.
 * @property-read DateTimeFormatter $datetime_formatter A datetime formatter.
 * @property-read DateFormatter $date_formatter A date formatter.
 * @property-read TimeFormatter $time_formatter A time formatter.
 *
 * @property-read string $standalone_abbreviated_days     Shortcut to `days/stand-alone/abbreviated`.
 * @property-read string $standalone_abbreviated_eras     Shortcut to `eras/eraAbbr`.
 * @property-read string $standalone_abbreviated_months   Shortcut to `months/stand-alone/abbreviated`.
 * @property-read string $standalone_abbreviated_quarters Shortcut to `quarters/stand-alone/abbreviated`.
 * @property-read string $standalone_narrow_days          Shortcut to `days/stand-alone/narrow`.
 * @property-read string $standalone_narrow_eras          Shortcut to `eras/eraNarrow`.
 * @property-read string $standalone_narrow_months        Shortcut to `months/stand-alone/narrow`.
 * @property-read string $standalone_narrow_quarters      Shortcut to `quarters/stand-alone/narrow`.
 * @property-read string $standalone_short_days           Shortcut to `days/stand-alone/short`.
 * @property-read string $standalone_short_eras           Shortcut to `eras/eraAbbr`.
 * @property-read string $standalone_short_months         Shortcut to `months/stand-alone/abbreviated`.
 * @property-read string $standalone_short_quarters       Shortcut to `quarters/stand-alone/abbreviated`.
 * @property-read string $standalone_wide_days            Shortcut to `days/stand-alone/wide`.
 * @property-read string $standalone_wide_eras            Shortcut to `eras/eraNames`.
 * @property-read string $standalone_wide_months          Shortcut to `months/stand-alone/wide`.
 * @property-read string $standalone_wide_quarters        Shortcut to `quarters/stand-alone/wide`.
 * @property-read string $abbreviated_days                Shortcut to `days/format/abbreviated`.
 * @property-read string $abbreviated_eras                Shortcut to `eras/eraAbbr`.
 * @property-read string $abbreviated_months              Shortcut to `months/format/abbreviated`.
 * @property-read string $abbreviated_quarters            Shortcut to `quarters/format/abbreviated`.
 * @property-read string $narrow_days                     Shortcut to `days/format/narrow`.
 * @property-read string $narrow_eras                     Shortcut to `eras/eraNarrow`.
 * @property-read string $narrow_months                   Shortcut to `months/format/narrow`.
 * @property-read string $narrow_quarters                 Shortcut to `quarters/format/narrow`.
 * @property-read string $short_days                      Shortcut to `days/format/short`.
 * @property-read string $short_eras                      Shortcut to `eras/eraAbbr`.
 * @property-read string $short_months                    Shortcut to `months/format/abbreviated`.
 * @property-read string $short_quarters                  Shortcut to `quarters/format/abbreviated`.
 * @property-read string $wide_days                       Shortcut to `days/format/wide`.
 * @property-read string $wide_eras                       Shortcut to `eras/eraNames`.
 * @property-read string $wide_months                     Shortcut to `months/format/wide`.
 * @property-read string $wide_quarters                   Shortcut to `quarters/format/wide`.
 */
class Calendar extends \ArrayObject
{
	use AccessorTrait;
	use LocalePropertyTrait;

	static private $era_translation = [

		'abbreviated' => 'eraAbbr',
		'narrow' => 'eraNarrow',
		'short' => 'eraAbbr',
		'wide' => 'eraNames'

	];

	/**
	 * @return DateTimeFormatter
	 */
	protected function lazy_get_datetime_formatter()
	{
		return new DateTimeFormatter($this);
	}

	/**
	 * @return DateFormatter
	 */
	protected function lazy_get_date_formatter()
	{
		return new DateFormatter($this);
	}

	/**
	 * @return TimeFormatter
	 */
	protected function lazy_get_time_formatter()
	{
		return new TimeFormatter($this);
	}

	public function __construct(Locale $locale, array $data)
	{
		$this->locale = $locale;

		parent::__construct($data);
	}

	public function __get($property)
	{
		if (preg_match('#^(standalone_)?(abbreviated|narrow|short|wide)_(days|eras|months|quarters)$#', $property, $matches))
		{
			list(, $standalone, $width, $type) = $matches;

			$data = $this[$type];

			if ($type == 'eras')
			{
				return $this->$property = $data[self::$era_translation[$width]];
			}

			$data = $data[$standalone ? 'stand-alone' : 'format'];

			if ($width == 'short' && empty($data[$width]))
			{
				$width = 'abbreviated';
			}

			return $this->$property = $data[$width];
		}

		return $this->__object_get($property);
	}
}
