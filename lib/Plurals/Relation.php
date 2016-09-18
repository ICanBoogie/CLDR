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

/**
 * Representation of a plural rule relation.
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Relations
 */
final class Relation
{
	const RANGE_SEPARATOR = '..';
	const MODULUS_SIGN = '%';

	/**
	 * @var array
	 */
	static private $instances = [];

	/**
	 * @param string $relation
	 *
	 * @return Relation
	 */
	static public function from($relation)
	{
		$instance = &self::$instances[$relation];

		if ($instance)
		{
			return $instance;
		}

		list($x_expression, $range) = self::parse_relation($relation);

		return $instance = new static($x_expression, $range);
	}

	/**
	 * @param $relation
	 *
	 * @return array
	 */
	static private function parse_relation($relation)
	{
		list($x_expression, $range_list) = explode('= ', $relation) + [ 1 => null ];
		list($x_expression, $negative) = self::parse_x_expression($x_expression);

		$range = self::parse_range_list($range_list);

		if ($negative)
		{
			$range = "!($range)";
		}

		return [ $x_expression, $range ];
	}

	/**
	 * @param string $x_expression
	 *
	 * @return array [ $x_expression, $negative ]
	 */
	static private function parse_x_expression($x_expression)
	{
		if (!$x_expression)
		{
			return [ null, false ];
		}

		$negative = false;

		if ($x_expression{strlen($x_expression) - 1} === '!')
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
	 * Parse a range list into a PHP statement.
	 *
	 * @param string $range_list
	 *
	 * @return string A PHP statement.
	 */
	static private function parse_range_list($range_list)
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

	/**
	 * @param string $range
	 *
	 * @return array
	 */
	static private function unwind_range($range)
	{
		list($start, $end) = explode(self::RANGE_SEPARATOR, $range);

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
	 * @param number $number
	 *
	 * @return int
	 */
	static private function precision_from($number)
	{
		return Number::precision_from($number);
	}

	/**
	 * @var string
	 */
	private $x_expression;

	/**
	 * @var string
	 */
	private $conditions;

	/**
	 * @param string $x_expression PHP code to evaluate `x`.
	 * @param string $conditions PHP code to evaluate.
	 */
	private function __construct($x_expression, $conditions)
	{
		$this->x_expression = $x_expression;
		$this->conditions = $conditions;
	}

	/**
	 * Resolve `x`.
	 *
	 * @param Operands $operands
	 *
	 * @return number
	 */
	public function resolve_x(Operands $operands)
	{
		extract($operands->to_array());

		return eval("return ($this->x_expression);");
	}

	/**
	 * @param Operands $operands
	 *
	 * @return bool `true` if the operands satisfy the rule, `false` otherwise.
	 */
	public function evaluate(Operands $operands)
	{
		if (!$this->x_expression)
		{
			return true;
		}

		// $x is typecasted as a string because `fmod(4.3, 3) != 1.3` BUT `(string) fmod(4.3, 3) == 1.3
		$x = (string) $this->resolve_x($operands);

		return eval("return $this->conditions;");
	}
}
