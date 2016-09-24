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
 * Representation of a locale.
 *
 * @property-read Repository $repository The repository provided during construct.
 * @property-read string $code The ISO code of the locale.
 * @property-read string $language The language code.
 * @property-read CalendarCollection $calendars The calendar collection of the locale.
 * @property-read Calendar $calendar The preferred calendar for this locale.
 * @property-read Numbers $numbers
 * @property-read LocalizedNumberFormatter $number_formatter
 * @property-read LocalizedCurrencyFormatter $currency_formatter
 * @property-read LocalizedListFormatter $list_formatter
 * @property-read ContextTransforms $context_transforms
 * @property-read Units $units
 */
class Locale extends AbstractSectionCollection
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
		if (!$code)
		{
			throw new \InvalidArgumentException("Locale identifier cannot be empty.");
		}

		parent::__construct($repository, "main/$code", self::$available_sections);

		$this->code = $code;
	}

	/**
	 * @return string
	 */
	protected function get_language()
	{
		list($language) = explode('-', $this->code, 2);

		return $language;
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

	/**
	 * @return LocalizedNumberFormatter
	 */
	protected function lazy_get_number_formatter()
	{
		return $this->localize($this->repository->number_formatter);
	}

	/**
	 * @return LocalizedCurrencyFormatter
	 */
	protected function lazy_get_currency_formatter()
	{
		return $this->localize($this->repository->currency_formatter);
	}

	/**
	 * @return LocalizedListFormatter
	 */
	protected function lazy_get_list_formatter()
	{
		return $this->localize($this->repository->list_formatter);
	}

	/**
	 * @return ContextTransforms
	 */
	protected function lazy_get_context_transforms()
	{
		return new ContextTransforms($this['contextTransforms']);
	}

	/**
	 * @return Units
	 */
	protected function lazy_get_units()
	{
		return new Units($this);
	}

	/**
	 * Localize the specified source.
	 *
	 * @param object|string $source_or_code The source to localize, or the locale code to localize
	 * this instance.
	 * @param array $options The options are passed to the localizer.
	 *
	 * @return mixed
	 */
	public function localize($source_or_code, array $options = [])
	{
		if (is_string($source_or_code))
		{
			return $this->repository->locales[$source_or_code]->localize($this, $options);
		}

		$constructor = $this->resolve_localize_constructor($source_or_code);

		if ($constructor)
		{
			return call_user_func($constructor, $source_or_code, $this, $options);
		}

		throw new \LogicException("Unable to localize source");
	}

	/**
	 * @param string $source
	 *
	 * @return string|null
	 */
	private function resolve_localize_constructor($source)
	{
		$class = get_class($source);

		if ($source instanceof LocalizeAwareInterface)
		{
			return $class . '::localize';
		}

		$base = basename(strtr($class, '\\', '/'));
		$constructor = __NAMESPACE__ . "\\Localized$base";

		if (!class_exists($constructor))
		{
			return null;
		}

		return $constructor . '::from';
	}

	/**
	 * Formats a number using {@link $number_formatter}.
	 *
	 * @param number $number
	 * @param string|null $pattern
	 * @param array $symbols
	 *
	 * @return string
	 *
	 * @see LocalizedNumberFormatter::format
	 */
	public function format_number($number, $pattern = null, array $symbols = [])
	{
		return $this->number_formatter->format($number, $pattern, $symbols);
	}

	/**
	 * @param number $number
	 * @param string|null $pattern
	 * @param array $symbols
	 *
	 * @return string
	 *
	 * @see LocalizedNumberFormatter::format
	 */
	public function format_percent($number, $pattern = null, array $symbols = [])
	{
		return $this->number_formatter->format(
			$number,
			$pattern ?: $this->numbers->percent_formats['standard'],
			$symbols
		);
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param number $number
	 * @param Currency|string $currency
	 * @param string $pattern
	 * @param array $symbols
	 *
	 * @return string
	 */
	public function format_currency(
		$number,
		$currency,
		$pattern = LocalizedCurrencyFormatter::PATTERN_STANDARD,
		array $symbols = []
	) {
		return $this->currency_formatter->format($number, $currency, $pattern, $symbols);
	}

	/**
	 * Formats a variable-length lists of things using {@link $list_formatter}.
	 *
	 * @param array $list The list to format.
	 * @param array|string $list_patterns_or_type A list patterns or a list patterns type (one
	 * of `LocalizedListFormatter::TYPE_*`).
	 *
	 * @return string
	 */
	public function format_list(array $list, $list_patterns_or_type = LocalizedListFormatter::TYPE_STANDARD)
	{
		return $this->list_formatter->format($list, $list_patterns_or_type);
	}

	/**
	 * Transforms a string depending on the context and the locale rules.
	 *
	 * @param string $str
	 * @param string $usage One of `ContextTransforms::USAGE_*`
	 * @param string $type One of `ContextTransforms::TYPE_*`
	 *
	 * @return string
	 */
	public function context_transform($str, $usage, $type)
	{
		return $this->context_transforms->transform($str, $usage, $type);
	}
}
