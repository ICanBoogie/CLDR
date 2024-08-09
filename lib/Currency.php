<?php

/**
 * CODE GENERATED; DO NOT EDIT.
 *
 * {@see \ICanBoogie\CLDR\Generator\Command\GenerateCurrency}
 */

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Supplemental\Fraction;

/**
 * Representation of a currency.
 *
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-numbers.html#Currencies
 */
final class Currency
{
	/**
	 * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-numbers-modern/main/en-001/currencies.json
	 */
	public const CODES =
    [
        'ADP',
        'AED',
        'AFA',
        'AFN',
        'ALK',
        'ALL',
        'AMD',
        'ANG',
        'AOA',
        'AOK',
        'AON',
        'AOR',
        'ARA',
        'ARL',
        'ARM',
        'ARP',
        'ARS',
        'ATS',
        'AUD',
        'AWG',
        'AZM',
        'AZN',
        'BAD',
        'BAM',
        'BAN',
        'BBD',
        'BDT',
        'BEC',
        'BEF',
        'BEL',
        'BGL',
        'BGM',
        'BGN',
        'BGO',
        'BHD',
        'BIF',
        'BMD',
        'BND',
        'BOB',
        'BOL',
        'BOP',
        'BOV',
        'BRB',
        'BRC',
        'BRE',
        'BRL',
        'BRN',
        'BRR',
        'BRZ',
        'BSD',
        'BTN',
        'BUK',
        'BWP',
        'BYB',
        'BYN',
        'BYR',
        'BZD',
        'CAD',
        'CDF',
        'CHE',
        'CHF',
        'CHW',
        'CLE',
        'CLF',
        'CLP',
        'CNH',
        'CNX',
        'CNY',
        'COP',
        'COU',
        'CRC',
        'CSD',
        'CSK',
        'CUC',
        'CUP',
        'CVE',
        'CYP',
        'CZK',
        'DDM',
        'DEM',
        'DJF',
        'DKK',
        'DOP',
        'DZD',
        'ECS',
        'ECV',
        'EEK',
        'EGP',
        'ERN',
        'ESA',
        'ESB',
        'ESP',
        'ETB',
        'EUR',
        'FIM',
        'FJD',
        'FKP',
        'FRF',
        'GBP',
        'GEK',
        'GEL',
        'GHC',
        'GHS',
        'GIP',
        'GMD',
        'GNF',
        'GNS',
        'GQE',
        'GRD',
        'GTQ',
        'GWE',
        'GWP',
        'GYD',
        'HKD',
        'HNL',
        'HRD',
        'HRK',
        'HTG',
        'HUF',
        'IDR',
        'IEP',
        'ILP',
        'ILR',
        'ILS',
        'INR',
        'IQD',
        'IRR',
        'ISJ',
        'ISK',
        'ITL',
        'JMD',
        'JOD',
        'JPY',
        'KES',
        'KGS',
        'KHR',
        'KMF',
        'KPW',
        'KRH',
        'KRO',
        'KRW',
        'KWD',
        'KYD',
        'KZT',
        'LAK',
        'LBP',
        'LKR',
        'LRD',
        'LSL',
        'LTL',
        'LTT',
        'LUC',
        'LUF',
        'LUL',
        'LVL',
        'LVR',
        'LYD',
        'MAD',
        'MAF',
        'MCF',
        'MDC',
        'MDL',
        'MGA',
        'MGF',
        'MKD',
        'MKN',
        'MLF',
        'MMK',
        'MNT',
        'MOP',
        'MRO',
        'MRU',
        'MTL',
        'MTP',
        'MUR',
        'MVP',
        'MVR',
        'MWK',
        'MXN',
        'MXP',
        'MXV',
        'MYR',
        'MZE',
        'MZM',
        'MZN',
        'NAD',
        'NGN',
        'NIC',
        'NIO',
        'NLG',
        'NOK',
        'NPR',
        'NZD',
        'OMR',
        'PAB',
        'PEI',
        'PEN',
        'PES',
        'PGK',
        'PHP',
        'PKR',
        'PLN',
        'PLZ',
        'PTE',
        'PYG',
        'QAR',
        'RHD',
        'ROL',
        'RON',
        'RSD',
        'RUB',
        'RUR',
        'RWF',
        'SAR',
        'SBD',
        'SCR',
        'SDD',
        'SDG',
        'SDP',
        'SEK',
        'SGD',
        'SHP',
        'SIT',
        'SKK',
        'SLE',
        'SLL',
        'SOS',
        'SRD',
        'SRG',
        'SSP',
        'STD',
        'STN',
        'SUR',
        'SVC',
        'SYP',
        'SZL',
        'THB',
        'TJR',
        'TJS',
        'TMM',
        'TMT',
        'TND',
        'TOP',
        'TPE',
        'TRL',
        'TRY',
        'TTD',
        'TWD',
        'TZS',
        'UAH',
        'UAK',
        'UGS',
        'UGX',
        'USD',
        'USN',
        'USS',
        'UYI',
        'UYP',
        'UYU',
        'UYW',
        'UZS',
        'VEB',
        'VED',
        'VEF',
        'VES',
        'VND',
        'VNN',
        'VUV',
        'WST',
        'XAF',
        'XAG',
        'XAU',
        'XBA',
        'XBB',
        'XBC',
        'XBD',
        'XCD',
        'XCG',
        'XDR',
        'XEU',
        'XFO',
        'XFU',
        'XOF',
        'XPD',
        'XPF',
        'XPT',
        'XRE',
        'XSU',
        'XTS',
        'XUA',
        'XXX',
        'YDD',
        'YER',
        'YUD',
        'YUM',
        'YUN',
        'YUR',
        'ZAL',
        'ZAR',
        'ZMK',
        'ZMW',
        'ZRN',
        'ZRZ',
        'ZWD',
        'ZWL',
        'ZWR',
    ];

	/**
	 * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/currencyData.json
	 */
	private const FRACTIONS =
    [
        'ADP' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'AFN' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'ALL' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'AMD' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'BHD' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'BIF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'BYN' => [
            '_rounding' => '0',
            '_digits' => '2',
        ],
        'BYR' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'CAD' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '5',
        ],
        'CHF' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '5',
        ],
        'CLF' => [
            '_rounding' => '0',
            '_digits' => '4',
        ],
        'CLP' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'COP' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'CRC' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'CZK' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'DEFAULT' => [
            '_rounding' => '0',
            '_digits' => '2',
        ],
        'DJF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'DKK' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '50',
        ],
        'ESP' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'GNF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'GYD' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'HUF' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'IDR' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'IQD' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'IRR' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'ISK' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'ITL' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'JOD' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'JPY' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'KMF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'KPW' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'KRW' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'KWD' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'LAK' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'LBP' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'LUF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'LYD' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'MGA' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'MGF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'MMK' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'MNT' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'MRO' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'MUR' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'NOK' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'OMR' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'PKR' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'PYG' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'RSD' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'RWF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'SEK' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'SLE' => [
            '_rounding' => '0',
            '_digits' => '2',
        ],
        'SLL' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'SOS' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'STD' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'SYP' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'TMM' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'TND' => [
            '_rounding' => '0',
            '_digits' => '3',
        ],
        'TRL' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'TWD' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'TZS' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'UGX' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'UYI' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'UYW' => [
            '_rounding' => '0',
            '_digits' => '4',
        ],
        'UZS' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'VEF' => [
            '_rounding' => '0',
            '_digits' => '2',
            '_cashRounding' => '0',
            '_cashDigits' => '0',
        ],
        'VND' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'VUV' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'XAF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'XOF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'XPF' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'YER' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'ZMK' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
        'ZWD' => [
            '_rounding' => '0',
            '_digits' => '0',
        ],
    ];

	private const FRACTIONS_FALLBACK = 'DEFAULT';

	/**
	 * Whether a currency code is defined.
	 *
	 * @param string $code
	 *     A currency code; for example, EUR.
	 */
	public static function is_defined(string $code): bool
	{
		return in_array($code, self::CODES);
	}

	/**
	 * @param string $code
	 *     A currency code; for example, EUR.
	 *
	 * @throws CurrencyNotDefined
	 */
	public static function assert_is_defined(string $code): void
	{
		self::is_defined($code)
			or throw new CurrencyNotDefined($code);
	}

	/**
	 * Returns a {@see CurrencyCode} of the specified code.
	 *
	 * @param string $code
	 *     A currency code; for example, EUR.
	 *
	 * @throws CurrencyNotDefined
	 */
	public static function of(string $code): self
	{
		static $instances;

		self::assert_is_defined($code);

		return $instances[$code] ??= new self($code, self::fraction_for($code));
	}

	/**
	 * Returns the {@see Fraction} for the specified currency code.
	 *
	 * @param string $code
	 * *     A currency code; for example, EUR.
	 */
	private static function fraction_for(string $code): Fraction
	{
		static $default_fraction;

		$data = self::FRACTIONS[$code] ?? null;

		if (!$data)
		{
			return $default_fraction ??= self::fraction_for(self::FRACTIONS_FALLBACK);
		}

		return Fraction::from($data);
	}

	/**
	 * @param string $code
	 *     A currency code; for example, EUR.
	*/
	private function __construct(
		public readonly string $code,
		public readonly Fraction $fraction,
	) {
	}

	/**
	 * Returns the {@see $code} of the currency.
	 */
	public function __toString() : string
	{
		return $this->code;
	}

	public function __serialize(): array
	{
		return [ 'code' => $this->code ];
	}

	/**
	 * @param array{ code: string } $data
	 */
	public function __unserialize(array $data): void
	{
		$this->code = $data['code'];
		$this->fraction = self::fraction_for($this->code);
	}

	/**
	 * Returns a localized currency.
	 */
	public function localize(Locale $locale): LocalizedCurrency
	{
		return new LocalizedCurrency($this, $locale);
	}
}
