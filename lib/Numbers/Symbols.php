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

		return $defaults ?? $defaults = self::from(self::DEFAULTS);
	}

	/**
	 * @readonly
	 * @var string
	 */
	public $decimal;

	/**
	 * @readonly
	 * @var string
	 */
	public $group;

	/**
	 * @readonly
	 * @var string
	 */
	public $list;

	/**
	 * @readonly
	 * @var string
	 */
	public $percentSign;

	/**
	 * @readonly
	 * @var string
	 */
	public $minusSign;

	/**
	 * @readonly
	 * @var string
	 */
	public $plusSign;

	/**
	 * @readonly
	 * @var string
	 */
	public $approximatelySign;

	/**
	 * @readonly
	 * @var string
	 */
	public $exponential;

	/**
	 * @readonly
	 * @var string
	 */
	public $superscriptingExponent;

	/**
	 * @readonly
	 * @var string
	 */
	public $perMille;

	/**
	 * @readonly
	 * @var string
	 */
	public $infinity;

	/**
	 * @readonly
	 * @var string
	 */
	public $nan;

	/**
	 * @readonly
	 * @var string
	 */
	public $currencyDecimal;

	/**
	 * @readonly
	 * @var string
	 */
	public $currencyGroup;

	/**
	 * @readonly
	 * @var string
	 */
	public $timeSeparator;

	public function __construct(
		string $decimal = '.',
		string $group = ',',
		string $list = ';',
		string $percentSign = '%',
		string $minusSign = '-',
		string $plusSign = '+',
		string $approximatelySign = '~',
		string $exponential = 'E',
		string $superscriptingExponent = '×',
		string $perMille = '‰',
		string $infinity = '∞',
		string $nan = '☹',
		string $currencyDecimal = '.',
		string $currencyGroup = ',',
		string $timeSeparator = ':'
	) {
		$this->decimal = $decimal;
		$this->group = $group;
		$this->list = $list;
		$this->percentSign = $percentSign;
		$this->minusSign = $minusSign;
		$this->plusSign = $plusSign;
		$this->approximatelySign = $approximatelySign;
		$this->exponential = $exponential;
		$this->superscriptingExponent = $superscriptingExponent;
		$this->perMille = $perMille;
		$this->infinity = $infinity;
		$this->nan = $nan;
		$this->currencyDecimal = $currencyDecimal;
		$this->currencyGroup = $currencyGroup;
		$this->timeSeparator = $timeSeparator;
	}
}
