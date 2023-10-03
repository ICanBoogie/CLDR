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
use InvalidArgumentException;
use LogicException;

use function str_replace;
use function strtr;

/**
 * Representation of a locale.
 *
 * @property-read string $language Unicode language.
 * @uses self::get_language()
 * @property-read CalendarCollection $calendars The calendar collection of the locale.
 * @uses self::lazy_get_calendars()
 * @property-read Calendar $calendar The preferred calendar for this locale.
 * @uses self::lazy_get_calendar()
 * @property-read Numbers $numbers
 * @uses self::lazy_get_numbers()
 * @property-read LocalizedNumberFormatter $number_formatter
 * @uses self::lazy_get_number_formatter()
 * @property-read LocalizedCurrencyFormatter $currency_formatter
 * @uses self::lazy_get_currency_formatter()
 * @property-read LocalizedListFormatter $list_formatter
 * @uses self::lazy_get_list_formatter()
 * @property-read ContextTransforms $context_transforms
 * @uses self::lazy_get_context_transforms()
 * @property-read Units $units
 * @uses self::lazy_get_units()
 */
class Locale extends AbstractSectionCollection
{
	use AccessorTrait;

	/**
	 * Where _key_ is an offset and _value_ and array where `0` is a pattern for the path and `1` the data path.
	 */
	private const OFFSET_MAPPING = [

		'ca-buddhist'            => [ 'cal-buddhist/{locale}/ca-buddhist',       'dates/calendars/buddhist' ],
		'ca-chinese'             => [ 'cal-chinese/{locale}/ca-chinese',         'dates/calendars/chinese' ],
		'ca-coptic'              => [ 'cal-coptic/{locale}/ca-coptic',           'dates/calendars/coptic' ],
		'ca-dangi'               => [ 'cal-dangi/{locale}/ca-dangi',             'dates/calendars/dangi' ],
		'ca-ethiopic'            => [ 'cal-ethiopic/{locale}/ca-ethiopic',       'dates/calendars/ethiopic' ],
		'ca-hebrew'              => [ 'cal-hebrew/{locale}/ca-hebrew',           'dates/calendars/hebrew' ],
		'ca-indian'              => [ 'cal-indian/{locale}/ca-indian',           'dates/calendars/indian' ],
		'ca-islamic'             => [ 'cal-islamic/{locale}/ca-islamic',         'dates/calendars/islamic' ],
		'ca-japanese'            => [ 'cal-japanese/{locale}/ca-japanese',       'dates/calendars/japanese' ],
		'ca-persian'             => [ 'cal-persian/{locale}/ca-persian',         'dates/calendars/persian' ],
		'ca-roc'                 => [ 'cal-roc/{locale}/ca-roc',                 'dates/calendars/roc' ],
		'ca-generic'             => [ 'dates/{locale}/ca-generic',               'dates/calendars/generic' ],
		'ca-gregorian'           => [ 'dates/{locale}/ca-gregorian',             'dates/calendars/gregorian' ],
		'dateFields'             => [ 'dates/{locale}/dateFields',               'dates/fields' ],
		'timeZoneNames'          => [ 'dates/{locale}/timeZoneNames',            'dates/timeZoneNames' ],
		'languages'              => [ 'localenames/{locale}/languages',          'localeDisplayNames/languages' ],
		'localeDisplayNames'     => [ 'localenames/{locale}/localeDisplayNames', 'localeDisplayNames' ],
		'scripts'                => [ 'localenames/{locale}/scripts',            'localeDisplayNames/scripts' ],
		'territories'            => [ 'localenames/{locale}/territories',        'localeDisplayNames/territories' ],
		'variants'               => [ 'localenames/{locale}/variants',           'localeDisplayNames/variants' ],
		'characters'             => [ 'misc/{locale}/characters',                'characters' ],
		'contextTransforms'      => [ 'misc/{locale}/contextTransforms',         'contextTransforms' ],
		'delimiters'             => [ 'misc/{locale}/delimiters',                'delimiters' ],
		'layout'                 => [ 'misc/{locale}/layout',                    'layout' ],
		'listPatterns'           => [ 'misc/{locale}/listPatterns',              'listPatterns' ],
		'posix'                  => [ 'misc/{locale}/posix',                     'posix' ],
		'currencies'             => [ 'numbers/{locale}/currencies',             'numbers/currencies' ],
		'numbers'                => [ 'numbers/{locale}/numbers',                'numbers' ],
		'measurementSystemNames' => [ 'units/{locale}/measurementSystemNames',   'localeDisplayNames/measurementSystemNames' ],
		'units'                  => [ 'units/{locale}/units',                    'units' ],

	];

	/**
	 * @param string $code
	 *     The ISO code of the locale.
	 */
	public function __construct(
		Repository $repository,
		public readonly string $code
	) {
		strlen($code) > 0 or throw new InvalidArgumentException("Locale identifier cannot be empty.");

		parent::__construct($repository);
	}

	public function offsetExists($offset): bool
	{
		return isset(self::OFFSET_MAPPING[$offset]);
	}

	protected function path_for(string $offset): string
	{
		return str_replace('{locale}', $this->code, self::OFFSET_MAPPING[$offset][0]);
	}

	protected function data_path_for(string $offset): string
	{
		return "main/$this->code/" . self::OFFSET_MAPPING[$offset][1];
	}

	protected function get_language(): string
	{
		[ $language ] = explode('-', $this->code, 2);

		return $language;
	}

	protected function lazy_get_calendars(): CalendarCollection
	{
		return new CalendarCollection($this);
	}

	protected function lazy_get_calendar(): Calendar
	{
		/** @var Calendar */
		return $this->calendars['gregorian']; // TODO-20131101: use preferred data
	}

	protected function lazy_get_numbers(): Numbers
	{
		/** @phpstan-ignore-next-line */
		return new Numbers($this, $this['numbers']);
	}

	protected function lazy_get_number_formatter(): LocalizedNumberFormatter
	{
		return $this->localize($this->repository->number_formatter);
	}

	protected function lazy_get_currency_formatter(): LocalizedCurrencyFormatter
	{
		return $this->localize($this->repository->currency_formatter);
	}

	protected function lazy_get_list_formatter(): LocalizedListFormatter
	{
		return $this->localize($this->repository->list_formatter);
	}

	protected function lazy_get_context_transforms(): ContextTransforms
	{
		try
		{
			/** @phpstan-ignore-next-line */
			return new ContextTransforms($this['contextTransforms']);
		}
		catch (ResourceNotFound $e)
		{
			// Not all locales have context transforms e.g. zh
			return new ContextTransforms([]);
		}
	}

	protected function lazy_get_units(): Units
	{
		return new Units($this);
	}

	/**
	 * Localize the specified source.
	 *
	 * @param object|string $source_or_code
	 *     The source to localize, or the locale code to localize this instance.
	 * @param array<string, mixed> $options
	 *     The options are passed to the localizer.
	 *
	 * @return mixed
	 */
	public function localize($source_or_code, array $options = [])
	{
		if (is_string($source_or_code))
		{
			/** @phpstan-ignore-next-line */
			return $this->repository->locales[$source_or_code]->localize($this, $options);
		}

		$constructor = $this->resolve_localize_constructor($source_or_code);

		if ($constructor)
		{
			return $constructor($source_or_code, $this, $options);
		}

		throw new LogicException("Unable to localize source");
	}

	/**
	 * @param object $source
	 */
	private function resolve_localize_constructor($source): ?callable
	{
		$class = get_class($source);

		if ($source instanceof Localizable)
		{
			return [ $class, 'localize' ]; // @phpstan-ignore-line
		}

		$base = basename(strtr($class, '\\', '/'));
		$constructor = __NAMESPACE__ . "\\Localized$base";

		if (!class_exists($constructor))
		{
			return null;
		}

		return [ $constructor, 'from' ]; // @phpstan-ignore-line
	}

	/**
	 * Formats a number using {@link $number_formatter}.
	 *
	 * @param float|int $number
	 *
	 * @see LocalizedNumberFormatter::format
	 */
	public function format_number($number, string $pattern = null): string
	{
		return $this->number_formatter->format($number, $pattern);
	}

	/**
	 * @param float|int|numeric-string $number
	 *
	 * @see LocalizedNumberFormatter::format
	 */
	public function format_percent(float|int|string $number, string $pattern = null): string
	{
		return $this->number_formatter->format(
			$number,
			$pattern ?? $this->numbers->percent_formats['standard']
		);
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param float|int|numeric-string $number
	 */
	public function format_currency(
		float|int|string $number,
		Currency|string $currency,
		string $pattern = LocalizedCurrencyFormatter::PATTERN_STANDARD
	): string {
		return $this->currency_formatter->format($number, $currency, $pattern);
	}

	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 * @param LocalizedListFormatter::TYPE_* $type
	 *
	 * @see LocalizedListFormatter::format()
	 */
	public function format_list(array $list, string $type = LocalizedListFormatter::TYPE_STANDARD): string
	{
		return $this->list_formatter->format($list, $type);
	}

	/**
	 * Transforms a string depending on the context and the locale rules.
	 *
	 * @param ContextTransforms::USAGE_* $usage
	 * @param ContextTransforms::TYPE_* $type
	 */
	public function context_transform(string $str, string $usage, string $type): string
	{
		return $this->context_transforms->transform($str, $usage, $type);
	}
}
