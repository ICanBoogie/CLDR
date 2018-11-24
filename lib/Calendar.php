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

use ICanBoogie\Accessor\AccessorTrait;

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
	const SHORTHANDS_REGEX = '#^(standalone_)?(abbreviated|narrow|short|wide)_(days|eras|months|quarters)$#';

	const WIDTH_ABBR = 'abbreviated';
	const WIDTH_NARROW = 'narrow';
	const WIDTH_SHORT = 'short';
	const WIDTH_WIDE = 'wide';

	const ERA_NAMES = 'eraNames';
	const ERA_ABBR = 'eraAbbr';
	const ERA_NARROW = 'eraNarrow';

	const CALENDAR_MONTHS = 'months';
	const CALENDAR_DAYS = 'days';
	const CALENDAR_QUARTERS = 'quarters';
	const CALENDAR_ERAS = 'eras';

	const CONTEXT_FORMAT = 'format';
	const CONTEXT_STAND_ALONE = 'stand-alone';

	use AccessorTrait;
	use LocalePropertyTrait;

	static private $era_widths_mapping = [

		self::WIDTH_ABBR => self::ERA_ABBR,
		self::WIDTH_NARROW => self::ERA_NARROW,
		self::WIDTH_SHORT => self::ERA_ABBR,
		self::WIDTH_WIDE => self::ERA_NAMES

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

	/**
	 * @var ContextTransforms
	 */
	private $context_transforms;

	/**
	 * @param Locale $locale
	 * @param array $data
	 */
	public function __construct(Locale $locale, array $data)
	{
		$this->locale = $locale;
		$this->context_transforms = $locale->context_transforms;

		$data = $this->transform_data($data);

		parent::__construct($data);
	}

	/**
	 * @inheritdoc
	 */
	public function __get($property)
	{
		if (!preg_match(self::SHORTHANDS_REGEX, $property, $matches))
		{
			return $this->accessor_get($property);
		}

		list(, $standalone, $width, $type) = $matches;

		$data = $this[$type];

		if ($type === self::CALENDAR_ERAS)
		{
			return $this->$property = $data[self::$era_widths_mapping[$width]];
		}

		$data = $data[$standalone ? self::CONTEXT_STAND_ALONE : self::CONTEXT_FORMAT];

		if ($width == self::WIDTH_SHORT && empty($data[$width]))
		{
			$width = self::WIDTH_ABBR;
		}

		return $this->$property = $data[$width];
	}

    /**
     * @param \DateTimeInterface|string|int $datetime
     * @param string $pattern_or_width_or_skeleton
     *
     * @return string
     *
     * @see \ICanBoogie\CLDR\DateTimeFormatter::format
     */
	public function format_datetime($datetime, $pattern_or_width_or_skeleton)
    {
        return $this->datetime_formatter->format($datetime, $pattern_or_width_or_skeleton);
    }

    /**
     * @param \DateTimeInterface|string|int $datetime
     * @param string $pattern_or_width_or_skeleton
     *
     * @return string
     *
     * @see \ICanBoogie\CLDR\DateFormatter::format
     */
	public function format_date($datetime, $pattern_or_width_or_skeleton)
    {
        return $this->date_formatter->format($datetime, $pattern_or_width_or_skeleton);
    }

    /**
     * @param \DateTimeInterface|string|int $datetime
     * @param string $pattern_or_width_or_skeleton
     *
     * @return string
     *
     * @see \ICanBoogie\CLDR\TimeFormatter::format
     */
    public function format_time($datetime, $pattern_or_width_or_skeleton)
    {
        return $this->time_formatter->format($datetime, $pattern_or_width_or_skeleton);
    }

	/**
	 * Transforms calendar data according to context transforms rules.
	 *
	 * @param array $data
	 *
	 * @return array
	 *
	 * @uses transform_months
	 * @uses transform_days
	 * @uses transform_quarters
	 */
	private function transform_data(array $data)
	{
		static $transformable = [

			self::CALENDAR_MONTHS,
			self::CALENDAR_DAYS,
			self::CALENDAR_QUARTERS

		];

		foreach ($transformable as $name)
		{
			array_walk($data[$name], function(array &$data, $context) use ($name) {

				$is_stand_alone = self::CONTEXT_STAND_ALONE === $context;

				array_walk($data, function (array &$names, $width) use ($name, $is_stand_alone) {

					$names = $this->{ 'transform_' . $name }($names, $width, $is_stand_alone);

				});

			});

		}

		if (isset($data[self::CALENDAR_ERAS]))
		{
			array_walk($data[self::CALENDAR_ERAS], function(&$names, $width) {

				$names = $this->transform_eras($names, $width);

			});
		}

		return $data;
	}

	/**
	 * Transforms month names according to context transforms rules.
	 *
	 * @param array $names
	 * @param string $width
	 * @param bool $standalone
	 *
	 * @return string
	 */
	private function transform_months(array $names, $width, $standalone)
	{
		return $this->transform_months_or_days(
			$names,
			$width,
			$standalone,
			ContextTransforms::USAGE_MONTH_STANDALONE_EXCEPT_NARROW
		);
	}

	/**
	 * Transforms day names according to context transforms rules.
	 *
	 * @param array $names
	 * @param string $width
	 * @param bool $standalone
	 *
	 * @return string
	 */
	private function transform_days($names, $width, $standalone)
	{
		return $this->transform_months_or_days(
			$names,
			$width,
			$standalone,
			ContextTransforms::USAGE_DAY_STANDALONE_EXCEPT_NARROW
		);
	}

	/**
	 * Transforms day names according to context transforms rules.
	 *
	 * @param array $names
	 * @param string $width
	 * @param bool $standalone
	 * @param string $usage
	 *
	 * @return string
	 */
	private function transform_months_or_days($names, $width, $standalone, $usage)
	{
		if ($width == self::WIDTH_NARROW || !$standalone)
		{
			return $names;
		}

		return $this->apply_transform(
			$names,
			$usage,
			ContextTransforms::TYPE_STAND_ALONE
		);
	}

	/**
	 * Transforms era names according to context transforms rules.
	 *
	 * @param array $names
	 * @param string $width
	 *
	 * @return string
	 */
	private function transform_eras(array $names, $width)
	{
		switch ($width)
		{
			case self::ERA_ABBR:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_ERA_ABBR,
					ContextTransforms::TYPE_STAND_ALONE
				);

			case self::ERA_NAMES:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_ERA_NAME,
					ContextTransforms::TYPE_STAND_ALONE
				);

			case self::ERA_NARROW:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_ERA_NARROW,
					ContextTransforms::TYPE_STAND_ALONE
				);
		}

		return $names; // @codeCoverageIgnore
	}

	/**
	 * Transforms quarters names according to context transforms rules.
	 *
	 * @param array $names
	 * @param string $width
	 * @param bool $standalone
	 *
	 * @return string
	 */
	private function transform_quarters(array $names, $width, $standalone)
	{
		if ($standalone)
		{
			if ($width != self::WIDTH_WIDE)
			{
				return $names;
			}

			return $this->apply_transform(
				$names,
				ContextTransforms::USAGE_QUARTER_STANDALONE_WIDE,
				ContextTransforms::TYPE_STAND_ALONE
			);
		}

		switch ($width)
		{
			case self::WIDTH_ABBR:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_QUARTER_ABBREVIATED,
					ContextTransforms::TYPE_STAND_ALONE
				);

			case self::WIDTH_WIDE:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_QUARTER_FORMAT_WIDE,
					ContextTransforms::TYPE_STAND_ALONE
				);

			case self::WIDTH_NARROW:

				return $this->apply_transform(
					$names,
					ContextTransforms::USAGE_QUARTER_NARROW,
					ContextTransforms::TYPE_STAND_ALONE
				);

		}

		return $names; // @codeCoverageIgnore
	}

	/**
	 * Applies transformation to names.
	 *
	 * @param array $names
	 * @param string $usage
	 * @param string $type
	 *
	 * @return array
	 */
	private function apply_transform(array $names, $usage, $type)
	{
		$context_transforms = $this->context_transforms;

		return array_map(function ($str) use ($context_transforms, $usage, $type) {

			return $context_transforms->transform($str, $usage, $type);

		}, $names);
	}
}
