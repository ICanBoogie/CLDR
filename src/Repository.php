<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\BCP47\BCP47;
use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\LocaleData;
use ICanBoogie\CLDR\Core\LocaleId;
use ICanBoogie\CLDR\General\Lists\ListFormatter;
use ICanBoogie\CLDR\General\Lists\ListPattern;
use ICanBoogie\CLDR\Numbers\CurrencyFormatter;
use ICanBoogie\CLDR\Numbers\NumberFormatter;
use ICanBoogie\CLDR\Numbers\NumberPattern;
use ICanBoogie\CLDR\Numbers\Symbols;
use ICanBoogie\CLDR\Provider\ResourceNotFound;
use ICanBoogie\CLDR\Supplemental\Plurals;
use ICanBoogie\CLDR\Supplemental\Supplemental;
use ICanBoogie\CLDR\Supplemental\Territory\Territory;
use ICanBoogie\CLDR\Supplemental\Territory\TerritoryCode;
use WeakMap;

use function array_shift;
use function explode;

/**
 * Representation of the CLDR.
 *
 * @property-read Supplemental $supplemental
 * @uses self::get_supplemental()
 * @property-read BCP47 $bcp47
 * @uses self::get_bcp47()
 * @property-read NumberFormatter $number_formatter
 * @uses self::get_number_formatter()
 * @property-read CurrencyFormatter $currency_formatter
 * @uses self::get_currency_formatter()
 * @property-read ListFormatter $list_formatter
 * @uses self::get_list_formatter()
 * @property-read Plurals $plurals
 * @uses self::get_plurals()
 *
 * @link https://github.com/unicode-org/cldr-json/tree/47.0.0
 */
final class Repository
{
    /**
     * @uses get_supplemental
     * @uses get_bcp47
     * @uses get_number_formatter
     * @uses get_currency_formatter
     * @uses get_list_formatter
     * @uses get_list_formatter
     * @uses get_plurals
     */
    use AccessorTrait;

    /**
     * @var array<string>
     */
    public array $available_locales;

    public function __construct(
        public readonly Provider $provider
    ) {
        $this->available_locales = LocaleData::AVAILABLE_LOCALES;
        $this->locales = new WeakMap();
        $this->territories = new WeakMap();
    }

    private Supplemental $supplemental;

    private function get_supplemental(): Supplemental
    {
        return $this->supplemental ??= new Supplemental($this);
    }

    private BCP47 $bcp47;

    private function get_bcp47(): BCP47
    {
        return $this->bcp47 ??= new BCP47($this);
    }

    private NumberFormatter $number_formatter;

    private function get_number_formatter(): NumberFormatter
    {
        return $this->number_formatter ??= new NumberFormatter();
    }

    private CurrencyFormatter $currency_formatter;

    private function get_currency_formatter(): CurrencyFormatter
    {
        return $this->currency_formatter ??= new CurrencyFormatter($this->get_number_formatter());
    }

    private ListFormatter $list_formatter;

    private function get_list_formatter(): ListFormatter
    {
        return $this->list_formatter ??= new ListFormatter();
    }

    private Plurals $plurals;

    private function get_plurals(): Plurals
    {
        return $this->plurals ??= new Plurals($this->get_supplemental()['plurals']);
    }

    /**
     * Fetches the data available at the specified path.
     *
     * @param string|null $data_path Path to the data to extract.
     *
     * @throws ResourceNotFound
     *
     * @phpstan-ignore-next-line
     */
    public function fetch(string $path, ?string $data_path = null): array
    {
        $data = $this->provider->provide($path);

        if ($data_path) {
            $data_path = explode('/', $data_path);

            while ($data_path) {
                $p = array_shift($data_path);
                $data = $data[$p];
            }
        }

        return $data;
    }

    /**
     * Format a number with the specified pattern.
     *
     * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
     * pattern contains '‰', the number will be multiplied by 1000.
     *
     * @param float|int|numeric-string $number
     *     The number to format.
     * @param string|NumberPattern $pattern
     *     The pattern used to format the number.
     */
    public function format_number(
        float|int|string $number,
        NumberPattern|string $pattern,
        ?Symbols $symbols = null,
    ): string {
        return $this->number_formatter->format($number, $pattern, $symbols);
    }

    /**
     * Format a number with the specified pattern.
     *
     * @param float|int|numeric-string $number
     *      The number to format.
     *
     * @see CurrencyFormatter::format()
     */
    public function format_currency(
        float|int|string $number,
        NumberPattern|string $pattern,
        ?Symbols $symbols = null,
        string $currencySymbol = CurrencyFormatter::DEFAULT_CURRENCY_SYMBOL
    ): string {
        return $this->currency_formatter->format($number, $pattern, $symbols, $currencySymbol);
    }

    /**
     * Formats variable-length lists of scalars.
     *
     * @param scalar[] $list
     *
     * @see ListFormatter::format()
     */
    public function format_list(array $list, ListPattern $list_pattern): string
    {
        return $this->list_formatter->format($list, $list_pattern);
    }

    /**
     * @var WeakMap<LocaleId, Locale>
     */
    private WeakMap $locales;

    /**
     * @param string|LocaleId $id
     *     A locale ID; for example, fr-BE.
     */
    public function locale_for(string|LocaleId $id): Locale
    {
        $id = LocaleId::from($id);

        return $this->locales[$id] ??= new Locale($this, $id);
    }

    /**
     * @var WeakMap<TerritoryCode, Territory>
     */
    private WeakMap $territories;

    /**
     * @param string|TerritoryCode $code
     *     A territory code; for example, CA.
     */
    public function territory_for(string|TerritoryCode $code): Territory
    {
        $code = TerritoryCode::of($code);

        return $this->territories[$code] ??= new Territory($this, $code);
    }
}
