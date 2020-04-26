<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Plurals;

use ICanBoogie\CLDR\Number;
use function array_merge;
use function explode;
use function extract;
use function implode;
use function rtrim;
use function str_repeat;
use function strlen;
use function strpos;
use function substr;
use function trim;

/**
 * Representation of a plural rule relation.
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Relations
 */
final class Relation
{
	public const RANGE_SEPARATOR = '..';
	public const MODULUS_SIGN = '%';

	/**
	 * @var Relation[]
	 */
	static private $instances = [];

	static public function from(string $relation): Relation
	{
		$instance = &self::$instances[$relation];

		return $instance ?? $instance = new self(...self::parse_relation($relation));
	}

	/**
	 * @return array [ string $x_expression, string $range ]
	 */
	static private function parse_relation(string $relation): array
	{
		[ $x_expression, $range_list ] = explode('= ', $relation) + [ 1 => null ];
		[ $x_expression, $negative ] = self::parse_x_expression($x_expression);

		$range = $range_list ? self::parse_range_list($range_list) : '($x == 0)';

		if ($negative)
		{
			$range = "!($range)";
		}

		return [ $x_expression, $range ];
	}

	/**
	 * @return array [ ?string $x_expression, bool $negative ]
	 */
	static private function parse_x_expression(string $x_expression): array
	{
		if (!$x_expression)
		{
			return [ null, false ];
		}

		$negative = false;

		if ($x_expression[strlen($x_expression) - 1] === '!')
		{
			$negative = true;

			$x_expression = substr($x_expression, 0, -1);
		}

		$x_expression = '$' . rtrim($x_expression);

		if (strpos($x_expression, self::MODULUS_SIGN) !== false)
		{
			list($operand, $modulus) = explode(self::MODULUS_SIGN, $x_expression);

			$operand = trim($operand);

			$x_expression = "fmod($operand, $modulus)";
		}

		return [ $x_expression, $negative ];
	}

	/**
	 * @return string A PHP statement.
	 */
	static private function parse_range_list(string $range_list): string
	{
		$ranges = [];

		foreach (explode(',', $range_list) as $range)
		{
			if (strpos($range, self::RANGE_SEPARATOR))
			{
				$ranges = array_merge($ranges, self::unwind_range($range));

				continue;
			}

			$ranges[] = "(\$x == $range)";
		}

		return implode(' || ', $ranges);
	}

	static private function unwind_range(string $range): array
	{
		[ $start, $end ] = explode(self::RANGE_SEPARATOR, $range);

		$precision = self::precision_from($start) ?: self::precision_from($end);
		$step = 1 / (int) ('1' . str_repeat('0', $precision));
		$end += $step;

		$ranges = [];

		for ( ; $start < $end ; $start += $step)
		{
			$ranges[] = "(\$x == $start)";
		}

		return $ranges;
	}

	/**
	 * @param int|float $number
	 */
	static private function precision_from($number): int
	{
		return Number::precision_from($number);
	}

	/**
	 * @var string|null
	 */
	private $x_expression;

	/**
	 * @var string
	 */
	private $conditions;

	private function __construct(?string $x_expression, string $conditions)
	{
		$this->x_expression = $x_expression;
		$this->conditions = $conditions;
	}

	/**
	 * @return int|float|null
	 */
	public function resolve_x(Operands $operands)
	{
		if ($this->x_expression === null) {
			return null;
		}

		$operands = $operands->to_array();

		extract($operands);

		return eval("return ($this->x_expression);");
	}

	/**
	 * Evaluate operands
	 */
	public function evaluate(Operands $operands): bool
	{
		if ($this->x_expression === null)
		{
			return true;
		}

		// $x is typecasted as a string because `fmod(4.3, 3) != 1.3` BUT `(string) fmod(4.3, 3) == 1.3
		$x = (string) $this->resolve_x($operands);

		return eval("return $this->conditions;");
	}
}
