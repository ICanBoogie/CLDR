<?php

/**
 * CODE GENERATED; DO NOT EDIT.
 *
 * {@see \ICanBoogie\CLDR\Generator\Command\GenerateTerritoryCode}
 */

namespace ICanBoogie\CLDR\Supplemental\Territory;

/**
 * A territory code.
 */
final class TerritoryCode
{
    /**
     * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-localenames-full/main/en-001/territories.json
     */
    public const CODES =
        [
            '001',
            '002',
            '003',
            '005',
            '009',
            '011',
            '013',
            '014',
            '015',
            '017',
            '018',
            '019',
            '021',
            '029',
            '030',
            '034',
            '035',
            '039',
            '053',
            '054',
            '057',
            '061',
            142,
            143,
            145,
            150,
            151,
            154,
            155,
            202,
            419,
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AI',
            'AL',
            'AM',
            'AO',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AW',
            'AX',
            'AZ',
            'BA',
            'BB',
            'BD',
            'BE',
            'BF',
            'BG',
            'BH',
            'BI',
            'BJ',
            'BL',
            'BM',
            'BN',
            'BO',
            'BQ',
            'BR',
            'BS',
            'BT',
            'BV',
            'BW',
            'BY',
            'BZ',
            'CA',
            'CC',
            'CD',
            'CF',
            'CG',
            'CH',
            'CI',
            'CK',
            'CL',
            'CM',
            'CN',
            'CO',
            'CP',
            'CQ',
            'CR',
            'CU',
            'CV',
            'CW',
            'CX',
            'CY',
            'CZ',
            'DE',
            'DG',
            'DJ',
            'DK',
            'DM',
            'DO',
            'DZ',
            'EA',
            'EC',
            'EE',
            'EG',
            'EH',
            'ER',
            'ES',
            'ET',
            'EU',
            'EZ',
            'FI',
            'FJ',
            'FK',
            'FM',
            'FO',
            'FR',
            'GA',
            'GB',
            'GD',
            'GE',
            'GF',
            'GG',
            'GH',
            'GI',
            'GL',
            'GM',
            'GN',
            'GP',
            'GQ',
            'GR',
            'GS',
            'GT',
            'GU',
            'GW',
            'GY',
            'HK',
            'HM',
            'HN',
            'HR',
            'HT',
            'HU',
            'IC',
            'ID',
            'IE',
            'IL',
            'IM',
            'IN',
            'IO',
            'IQ',
            'IR',
            'IS',
            'IT',
            'JE',
            'JM',
            'JO',
            'JP',
            'KE',
            'KG',
            'KH',
            'KI',
            'KM',
            'KN',
            'KP',
            'KR',
            'KW',
            'KY',
            'KZ',
            'LA',
            'LB',
            'LC',
            'LI',
            'LK',
            'LR',
            'LS',
            'LT',
            'LU',
            'LV',
            'LY',
            'MA',
            'MC',
            'MD',
            'ME',
            'MF',
            'MG',
            'MH',
            'MK',
            'ML',
            'MM',
            'MN',
            'MO',
            'MP',
            'MQ',
            'MR',
            'MS',
            'MT',
            'MU',
            'MV',
            'MW',
            'MX',
            'MY',
            'MZ',
            'NA',
            'NC',
            'NE',
            'NF',
            'NG',
            'NI',
            'NL',
            'NO',
            'NP',
            'NR',
            'NU',
            'NZ',
            'OM',
            'PA',
            'PE',
            'PF',
            'PG',
            'PH',
            'PK',
            'PL',
            'PM',
            'PN',
            'PR',
            'PS',
            'PT',
            'PW',
            'PY',
            'QA',
            'QO',
            'RE',
            'RO',
            'RS',
            'RU',
            'RW',
            'SA',
            'SB',
            'SC',
            'SD',
            'SE',
            'SG',
            'SH',
            'SI',
            'SJ',
            'SK',
            'SL',
            'SM',
            'SN',
            'SO',
            'SR',
            'SS',
            'ST',
            'SV',
            'SX',
            'SY',
            'SZ',
            'TA',
            'TC',
            'TD',
            'TF',
            'TG',
            'TH',
            'TJ',
            'TK',
            'TL',
            'TM',
            'TN',
            'TO',
            'TR',
            'TT',
            'TV',
            'TW',
            'TZ',
            'UA',
            'UG',
            'UM',
            'UN',
            'US',
            'UY',
            'UZ',
            'VA',
            'VC',
            'VE',
            'VG',
            'VI',
            'VN',
            'VU',
            'WF',
            'WS',
            'XA',
            'XB',
            'XK',
            'YE',
            'YT',
            'ZA',
            'ZM',
            'ZW',
            'ZZ',
        ];

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
     * @throws TerritoryNotDefined
     */
    public static function assert_is_defined(string $code): void
    {
        self::is_defined($code)
            or throw new TerritoryNotDefined($code);
    }

    /**
     * Returns a {@see TerritoryCode} of the specified code.
     *
     * @param string $code
     *     A currency code; for example, EUR.
     *
     * @throws TerritoryNotDefined
     */
    public static function of(string $code): self
    {
        static $instances;

        self::assert_is_defined($code);

        return $instances[$code] ??= new self($code);
    }

    /**
     * @param string $value
     *     A territory code; for example, CA.
    */
    private function __construct(
        public readonly string $value,
    ) {
    }

    /**
     * Returns the {@see $value} of the currency.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    public function __serialize(): array
    {
        return [ 'value' => $this->value ];
    }

    /**
     * @param array{ value: string } $data
     */
    public function __unserialize(array $data): void
    {
        $this->value = $data['value'];
    }
}
