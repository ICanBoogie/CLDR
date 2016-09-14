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
 * @see http://unicode.org/reports/tr35/tr35-general.html#contextTransformUsage_type_attribute_values
 */
class ContextTransforms
{
	const USAGE_ALL = 'all';
	const USAGE_LANGUAGE = 'language';
	const USAGE_SCRIPT = 'script';
	const USAGE_TERRITORY = 'territory';
	const USAGE_VARIANT = 'variant';
	const USAGE_KEY = 'key';
	const USAGE_KEYVALUE = 'keyValue';
	const USAGE_MONTH_FORMAT_EXCEPT_NARROW = 'month-format-except-narrow';
	const USAGE_MONTH_STANDALONE_EXCEPT_NARROW = 'month-standalone-except-narrow';
	const USAGE_MONTH_NARROW = 'month-narrow';
	const USAGE_DAY_FORMAT_EXCEPT_NARROW = 'day-format-except-narrow';
	const USAGE_DAY_STANDALONE_EXCEPT_NARROW = 'day-standalone-except-narrow';
	const USAGE_DAY_NARROW = 'day-narrow';
	const USAGE_ERA_NAME = 'era-name';
	const USAGE_ERA_ABBR = 'era-abbr';
	const USAGE_ERA_NARROW = 'era-narrow';
	const USAGE_QUARTER_FORMAT_WIDE = 'quarter-format-wide';
	const USAGE_QUARTER_STANDALONE_WIDE = 'quarter-standalone-wide';
	const USAGE_QUARTER_ABBREVIATED = 'quarter-abbreviated';
	const USAGE_QUARTER_NARROW = 'quarter-narrow';
	const USAGE_CALENDAR_FIELD = 'calendar-field';
	const USAGE_ZONE_EXEMPLARCITY = 'zone-exemplarCity';
	const USAGE_ZONE_LONG = 'zone-long';
	const USAGE_ZONE_SHORT = 'zone-short';
	const USAGE_METAZONE_LONG = 'metazone-long';
	const USAGE_METAZONE_SHORT = 'metazone-short';
	const USAGE_SYMBOL = 'symbol';
	const USAGE_CURRENCYNAME = 'currencyName';
	const USAGE_CURRENCYNAME_COUNT = 'currencyName-count';
	const USAGE_RELATIVE = 'relative';
	const USAGE_UNIT_PATTERN = 'unit-pattern';
	const USAGE_NUMBER_SPELLOUT = 'number-spellout';

	const TYPE_UILIST_OR_MENU = 'uiListOrMenu';
	const TYPE_STAND_ALONE = 'stand-alone';

	const TRANSFORM_TITLECASE_FIRSTWORD = 'titlecase-firstword';
	const TRANSFORM_NO_CHANGE = 'no-change';

	/**
	 * @var array
	 */
	private $rules;

	/**
	 * @param array $rules
	 */
	public function __construct(array $rules)
	{
		$this->rules = $rules;
	}

	/**
	 * @param string $str
	 * @param string $usage One of `USAGE_*`.
	 * @param string $type One of `TYPE_*`.
	 *
	 * @return string
	 */
	public function transform($str, $usage, $type)
	{
		$rules = $this->rules;

		if (empty($rules[$usage]))
		{
			$usage = self::USAGE_ALL;
		}

		if (empty($rules[$usage][$type]))
		{
			return $str;
		}

		$transform = $rules[$usage][$type];

		switch ($transform)
		{
			case self::TRANSFORM_TITLECASE_FIRSTWORD:
				return $this->titlecase_firstword($str);

			case self::TRANSFORM_NO_CHANGE;
				return $str;

			default:
				throw new \LogicException("Don't know how to apply transform: $transform");
		}
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	private function titlecase_firstword($str)
	{
		return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
	}
}
