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

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Number;

/**
 * Representation of plural operands.
 *
 * @property-read int|float $n
 * @property-read int $i
 * @property-read int $v
 * @property-read int $w
 * @property-read int $f
 * @property-read int $t
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Operands
 */
final class Operands
{
	/**
	 * @uses get_n
	 * @uses get_i
	 * @uses get_v
	 * @uses get_w
	 * @uses get_f
	 * @uses get_t
	 */
	use AccessorTrait;

	/**
	 * @var Operands[]
	 */
	static private $instances = [];

	/**
	 * @param int|float $number
	 */
	static public function from($number): self
	{
		$instance = &self::$instances["number-$number"];

		return $instance ?? $instance = new self($number);
	}

	/**
	 * @var int|float
	 */
	private $n;

	private function get_n()
	{
		return $this->n;
	}

	/**
	 * @var int
	 */
	private $i;

	private function get_i(): int
	{
		return $this->i;
	}

	/**
	 * @var int
	 */
	private $v;

	private function get_v(): int
	{
		return $this->v;
	}

	/**
	 * @var int
	 */
	private $w;

	private function get_w(): int
	{
		return $this->w;
	}

	/**
	 * @var int
	 */
	private $f;

	private function get_f(): int
	{
		return $this->f;
	}

	/**
	 * @var int
	 */
	private $t;

	private function get_t(): int
	{
		return $this->t;
	}

	/**
	 * @param int|float $number
	 */
	private function __construct($number)
	{
		[ $integer, $precision ] = Number::parse($number);

		$this->n = abs($number);
		$this->i = $integer;

		if ($precision === null)
		{
			$this->v = 0;
			$this->w = 0;
			$this->f = 0;
			$this->t = 0;
		}
		else
		{
			$this->v = strlen($precision);
			$this->w = strlen(rtrim($precision, '0'));
			$this->f = (int) ltrim($precision, '0');
			$this->t = (int) trim($precision, '0');
		}
	}

	/**
	 * @return array An array made of [ $n, $i, $v, $w, $f, $t ].
	 */
	public function to_array(): array
	{
		return [

			'n' => $this->n,
			'i' => $this->i,
			'v' => $this->v,
			'w' => $this->w,
			'f' => $this->f,
			't' => $this->t

		];
	}
}
