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
 * @property-read int $n
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
	use AccessorTrait;

	static private $instances = [];

	static public function from($number)
	{
		$instance = &self::$instances["number-$number"];

		return $instance ?: $instance = new static($number);
	}

	/**
	 * @var number
	 */
	private $n;

	/**
	 * @return int
	 */
	protected function get_n()
	{
		return $this->n;
	}

	/**
	 * @var int
	 */
	private $i;

	/**
	 * @return int
	 */
	protected function get_i()
	{
		return $this->i;
	}

	/**
	 * @var int
	 */
	private $v;

	/**
	 * @return int
	 */
	protected function get_v()
	{
		return $this->v;
	}

	/**
	 * @var int
	 */
	private $w;

	/**
	 * @return int
	 */
	protected function get_w()
	{
		return $this->w;
	}

	/**
	 * @var int
	 */
	private $f;

	/**
	 * @return int
	 */
	protected function get_f()
	{
		return $this->f;
	}

	/**
	 * @var int
	 */
	private $t;

	/**
	 * @return int
	 */
	protected function get_t()
	{
		return $this->t;
	}

	/**
	 * @param number $number
	 */
	private function __construct($number)
	{
		list($integer, $precision) = Number::parse($number);

		$this->n = abs($number);
		$this->i = $integer;
		$this->v = strlen($precision);
		$this->w = strlen(rtrim($precision, '0'));
		$this->f = (int) ltrim($precision, '0');
		$this->t = (int) trim($precision, '0');
	}

	/**
	 * @return array An array made of [ $n, $i, $v, $w, $f, $t ].
	 */
	public function to_array()
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
