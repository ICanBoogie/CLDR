<?php

namespace ICanBoogie\CLDR\Numbers;

/**
 * Defines the localized symbols that are commonly used when formatting numbers in a given locale.
 *
 * @see https://unicode.org/reports/tr35/tr35-numbers.html#Number_Symbols
 */
final class Symbols
{
	public const DEFAULTS = [

		'decimal' => '.',
		'group' => ',',
		'list' => ';',
		'percentSign' => '%',
		'minusSign' => '-',
		'plusSign' => '+',
		'approximatelySign' => '~',
		'exponential' => 'E',
		'superscriptingExponent' => '×',
		'perMille' => '‰',
		'infinity' => '∞',
		'nan' => '☹',
		'currencyDecimal' => '.',
		'currencyGroup' => ',',
		'timeSeparator' => ':',

	];

	/**
	 * @param array<string, string> $symbols
	 */
	public static function from(array $symbols): self
	{
		$symbols += self::DEFAULTS;

		return new self(
			$symbols['decimal'],
			$symbols['group'],
			$symbols['list'],
			$symbols['percentSign'],
			$symbols['minusSign'],
			$symbols['plusSign'],
			$symbols['approximatelySign'],
			$symbols['exponential'],
			$symbols['superscriptingExponent'],
			$symbols['perMille'],
			$symbols['infinity'],
			$symbols['nan'],
			$symbols['currencyDecimal'],
			$symbols['currencyGroup'],
			$symbols['timeSeparator']
		);
	}

	public static function defaults(): self
	{
		static $defaults;

		return $defaults ??= self::from(self::DEFAULTS);
	}

	public function __construct(
		public readonly string $decimal = '.',
		public readonly string $group = ',',
		public readonly string $list = ';',
		public readonly string $percentSign = '%',
		public readonly string $minusSign = '-',
		public readonly string $plusSign = '+',
		public readonly string $approximatelySign = '~',
		public readonly string $exponential = 'E',
		public readonly string $superscriptingExponent = '×',
		public readonly string $perMille = '‰',
		public readonly string $infinity = '∞',
		public readonly string $nan = '☹',
		public readonly string $currencyDecimal = '.',
		public readonly string $currencyGroup = ',',
		public readonly string $timeSeparator = ':'
	) {
	}
}
