<?php

namespace ICanBoogie\CLDR;

use Closure;
use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Locale\HasContextTransforms;

use function str_replace;

/**
 * Representation of a locale.
 *
 * @property-read string $language Unicode language.
 * @uses self::get_language()
 * @property-read CalendarCollection $calendars The calendar collection of the locale.
 * @uses self::get_calendars()
 * @property-read Calendar $calendar The preferred calendar for this locale.
 * @uses self::get_calendar()
 * @property-read Numbers $numbers
 * @uses self::get_numbers()
 * @property-read LocalizedNumberFormatter $number_formatter
 * @uses self::get_number_formatter()
 * @property-read LocalizedCurrencyFormatter $currency_formatter
 * @uses self::get_currency_formatter()
 * @property-read LocalizedListFormatter $list_formatter
 * @uses self::get_list_formatter()
 * @property-read ContextTransforms $context_transforms
 * @uses self::get_context_transforms()
 * @property-read Units $units
 * @uses self::get_units()
 *
 * @extends AbstractSectionCollection<string>
 * @implements Localizable<Locale, LocalizedLocale>
 */
class Locale extends AbstractSectionCollection implements Localizable, Warmable
{
    /**
     * @uses get_context_transforms
     * @uses get_currency_formatter
     * @uses get_calendar
     * @uses get_calendars
     * @uses get_language
     * @uses get_list_formatter
     * @uses get_number_formatter
     * @uses get_units
     */
    use AccessorTrait;

    /**
     * Where _key_ is an offset and _value_ and array where `0` is a pattern for the path and `1` the data path.
     */
    private const OFFSET_MAPPING = [

        'ca-buddhist' => [ 'cal-buddhist/{locale}/ca-buddhist', 'dates/calendars/buddhist' ],
        'ca-chinese' => [ 'cal-chinese/{locale}/ca-chinese', 'dates/calendars/chinese' ],
        'ca-coptic' => [ 'cal-coptic/{locale}/ca-coptic', 'dates/calendars/coptic' ],
        'ca-dangi' => [ 'cal-dangi/{locale}/ca-dangi', 'dates/calendars/dangi' ],
        'ca-ethiopic' => [ 'cal-ethiopic/{locale}/ca-ethiopic', 'dates/calendars/ethiopic' ],
        'ca-hebrew' => [ 'cal-hebrew/{locale}/ca-hebrew', 'dates/calendars/hebrew' ],
        'ca-indian' => [ 'cal-indian/{locale}/ca-indian', 'dates/calendars/indian' ],
        'ca-islamic' => [ 'cal-islamic/{locale}/ca-islamic', 'dates/calendars/islamic' ],
        'ca-japanese' => [ 'cal-japanese/{locale}/ca-japanese', 'dates/calendars/japanese' ],
        'ca-persian' => [ 'cal-persian/{locale}/ca-persian', 'dates/calendars/persian' ],
        'ca-roc' => [ 'cal-roc/{locale}/ca-roc', 'dates/calendars/roc' ],
        'ca-generic' => [ 'dates/{locale}/ca-generic', 'dates/calendars/generic' ],
        'ca-gregorian' => [ 'dates/{locale}/ca-gregorian', 'dates/calendars/gregorian' ],
        'dateFields' => [ 'dates/{locale}/dateFields', 'dates/fields' ],
        'timeZoneNames' => [ 'dates/{locale}/timeZoneNames', 'dates/timeZoneNames' ],
        'languages' => [ 'localenames/{locale}/languages', 'localeDisplayNames/languages' ],
        'localeDisplayNames' => [ 'localenames/{locale}/localeDisplayNames', 'localeDisplayNames' ],
        'scripts' => [ 'localenames/{locale}/scripts', 'localeDisplayNames/scripts' ],
        'territories' => [ 'localenames/{locale}/territories', 'localeDisplayNames/territories' ],
        'variants' => [ 'localenames/{locale}/variants', 'localeDisplayNames/variants' ],
        'characters' => [ 'misc/{locale}/characters', 'characters' ],
        'contextTransforms' => [ 'misc/{locale}/contextTransforms', 'contextTransforms' ],
        'delimiters' => [ 'misc/{locale}/delimiters', 'delimiters' ],
        'layout' => [ 'misc/{locale}/layout', 'layout' ],
        'listPatterns' => [ 'misc/{locale}/listPatterns', 'listPatterns' ],
        'posix' => [ 'misc/{locale}/posix', 'posix' ],
        'currencies' => [ 'numbers/{locale}/currencies', 'numbers/currencies' ],
        'numbers' => [ 'numbers/{locale}/numbers', 'numbers' ],
        'measurementSystemNames' => [
            'units/{locale}/measurementSystemNames',
            'localeDisplayNames/measurementSystemNames'
        ],
        'units' => [ 'units/{locale}/units', 'units' ],

    ];

    public function __construct(
        Repository $repository,
        public readonly LocaleId $id
    ) {
        parent::__construct($repository);
    }

    public function offsetGet($offset)
    {
        // Not all locales have context transforms
        if ($offset === 'contextTransforms' && !HasContextTransforms::for_locale($this->id)) {
            return [];
        }

        return parent::offsetGet($offset);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset(self::OFFSET_MAPPING[$offset]);
    }

    /**
     * Warm up with locale relevant data.
     */
    public function warm_up(Closure $progress): void
    {
        $progress("Warming up locale '{$this->id->value}':");

        foreach (array_keys(self::OFFSET_MAPPING) as $offset) {
            $progress("- $offset");
            $this->offsetGet($offset);
        }
    }

    protected function path_for(string $offset): string
    {
        return str_replace('{locale}', $this->id->value, self::OFFSET_MAPPING[$offset][0]);
    }

    protected function data_path_for(string $offset): string
    {
        return "main/{$this->id->value}/" . self::OFFSET_MAPPING[$offset][1];
    }

    private function get_language(): string
    {
        [ $language ] = explode('-', $this->id->value, 2);

        return $language;
    }

    private CalendarCollection $calendars;

    private function get_calendars(): CalendarCollection
    {
        return $this->calendars ??= new CalendarCollection($this);
    }

    private Calendar $calendar;

    private function get_calendar(): Calendar
    {
        return $this->calendar ??= $this->get_calendars()['gregorian']; // TODO-20131101: use preferred data
    }

    private Numbers $numbers;

    private function get_numbers(): Numbers
    {
        return $this->numbers ??= new Numbers($this, $this['numbers']);
    }

    private LocalizedNumberFormatter $number_formatter;

    private function get_number_formatter(): LocalizedNumberFormatter
    {
        return $this->number_formatter ??= $this->repository->number_formatter->localized($this);
    }

    private LocalizedCurrencyFormatter $currency_formatter;

    private function get_currency_formatter(): LocalizedCurrencyFormatter
    {
        return $this->currency_formatter ??= $this->repository->currency_formatter->localized($this);
    }

    private LocalizedListFormatter $list_formatter;

    private function get_list_formatter(): LocalizedListFormatter
    {
        return $this->list_formatter ??= $this->repository->list_formatter->localized($this);
    }

    private ContextTransforms $context_transforms;

    private function get_context_transforms(): ContextTransforms
    {
        return $this->context_transforms ??= new ContextTransforms($this['contextTransforms']);
    }

    private Units $units;

    private function get_units(): Units
    {
        return $this->units ??= new Units($this);
    }

    public function localized(Locale|LocaleId|string $locale): LocalizedLocale
    {
        if (!$locale instanceof self) {
            $locale = $this->repository->locale_for($locale);
        }

        return new LocalizedLocale($this, $locale);
    }

    /**
     * Formats a number using {@see $number_formatter}.
     *
     * @param float|int $number
     *
     * @see LocalizedNumberFormatter::format
     */
    public function format_number($number, string $pattern = null): string
    {
        return $this->get_number_formatter()->format($number, $pattern);
    }

    /**
     * @param float|int|numeric-string $number
     *
     * @see LocalizedNumberFormatter::format
     */
    public function format_percent(float|int|string $number, string $pattern = null): string
    {
        return $this->get_number_formatter()->format(
            $number,
            $pattern ?? $this->get_numbers()->percent_formats['standard']
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
        return $this->get_currency_formatter()->format($number, $currency, $pattern);
    }

    /**
     * Formats variable-length lists of scalars.
     *
     * @param scalar[] $list
     *
     * @see LocalizedListFormatter::format()
     */
    public function format_list(array $list, ListType $type = ListType::STANDARD): string
    {
        return $this->get_list_formatter()->format($list, $type);
    }

    /**
     * Transforms a string depending on the context and the locale rules.
     *
     * @param ContextTransforms::USAGE_* $usage
     * @param ContextTransforms::TYPE_* $type
     */
    public function context_transform(string $str, string $usage, string $type): string
    {
        return $this->get_context_transforms()->transform($str, $usage, $type);
    }
}
