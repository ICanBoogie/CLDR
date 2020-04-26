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

use LogicException;
use function mb_strtoupper;
use function mb_substr;

/**
 * @see http://unicode.org/reports/tr35/tr35-general.html#contextTransformUsage_type_attribute_values
 */
final class ContextTransforms
{
	public const USAGE_ALL = 'all';
	public const USAGE_LANGUAGE = 'language';
	public const USAGE_SCRIPT = 'script';
	public const USAGE_TERRITORY = 'territory';
	public const USAGE_VARIANT = 'variant';
	public const USAGE_KEY = 'key';
	public const USAGE_KEYVALUE = 'keyValue';
	public const USAGE_MONTH_FORMAT_EXCEPT_NARROW = 'month-format-except-narrow';
	public const USAGE_MONTH_STANDALONE_EXCEPT_NARROW = 'month-standalone-except-narrow';
	public const USAGE_MONTH_NARROW = 'month-narrow';
	public const USAGE_DAY_FORMAT_EXCEPT_NARROW = 'day-format-except-narrow';
	public const USAGE_DAY_STANDALONE_EXCEPT_NARROW = 'day-standalone-except-narrow';
	public const USAGE_DAY_NARROW = 'day-narrow';
	public const USAGE_ERA_NAME = 'era-name';
	public const USAGE_ERA_ABBR = 'era-abbr';
	public const USAGE_ERA_NARROW = 'era-narrow';
	public const USAGE_QUARTER_FORMAT_WIDE = 'quarter-format-wide';
	public const USAGE_QUARTER_STANDALONE_WIDE = 'quarter-standalone-wide';
	public const USAGE_QUARTER_ABBREVIATED = 'quarter-abbreviated';
	public const USAGE_QUARTER_NARROW = 'quarter-narrow';
	public const USAGE_CALENDAR_FIELD = 'calendar-field';
	public const USAGE_ZONE_EXEMPLARCITY = 'zone-exemplarCity';
	public const USAGE_ZONE_LONG = 'zone-long';
	public const USAGE_ZONE_SHORT = 'zone-short';
	public const USAGE_METAZONE_LONG = 'metazone-long';
	public const USAGE_METAZONE_SHORT = 'metazone-short';
	public const USAGE_SYMBOL = 'symbol';
	public const USAGE_CURRENCYNAME = 'currencyName';
	public const USAGE_CURRENCYNAME_COUNT = 'currencyName-count';
	public const USAGE_RELATIVE = 'relative';
	public const USAGE_UNIT_PATTERN = 'unit-pattern';
	public const USAGE_NUMBER_SPELLOUT = 'number-spellout';

	public const TYPE_UILIST_OR_MENU = 'uiListOrMenu';
	public const TYPE_STAND_ALONE = 'stand-alone';

	public const TRANSFORM_TITLECASE_FIRSTWORD = 'titlecase-firstword';
	public const TRANSFORM_NO_CHANGE = 'no-change';

	/**
	 * @var array
	 */
	private $rules;

	public function __construct(array $rules)
	{
		$this->rules = $rules;
	}

	/**
	 * @param string $usage One of `USAGE_*`.
	 * @param string $type One of `TYPE_*`.
	 */
	public function transform(string $str, string $usage, string $type): string
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
				throw new LogicException("Don't know how to apply transform: $transform");
		}
	}

	private function titlecase_firstword(string $str): string
	{
		return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
	}
}
