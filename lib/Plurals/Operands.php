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

use function abs;

/**
 * Representation of plural operands.
 *
 * @internal
 *
 * @property-read float|int $n
 * @property-read int $i
 * @property-read int $v
 * @property-read int $w
 * @property-read int $f
 * @property-read int $t
 * @property-read int $e
 *
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Operands
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
	 * @uses get_e
	 */
	use AccessorTrait;

	/**
	 * @param numeric $number
	 */
	static public function from($number): self
	{
		return OperandsCache::get($number, static function () use ($number): self {
			return new self($number);
		});
	}

	/**
	 * @var float|int
	 */
	private $n;

	/**
	 * @return float|int
	 */
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
	 * @var int
	 */
	private $e; // @phpstan-ignore-line

	private function get_e(): int
	{
		return $this->e;
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	private function __construct(float|int|string $number)
	{
		$number = $this->expand_compact_decimal_exponent($number);

		[ $integer, $fractional ] = Number::parse($number);

		$n = abs($number);

		if ($fractional === null || (int) $fractional === 0)
		{
			$n = (int) $n;
		}

		$this->n = $n;
		$this->i = $integer;

		if ($fractional === null)
		{
			$this->v = 0;
			$this->w = 0;
			$this->f = 0;
			$this->t = 0;
		}
		else
		{
			$this->v = strlen($fractional);
			$this->w = strlen(rtrim($fractional, '0'));
			$this->f = (int) ltrim($fractional, '0');
			$this->t = (int) trim($fractional, '0');
		}
	}

	/**
	 * @return array{ n: float|int, i: int, v: int, w: int, f: int, t: int, e: int }
	 */
	public function to_array(): array
	{
		return [

			'n' => $this->n,
			'i' => $this->i,
			'v' => $this->v,
			'w' => $this->w,
			'f' => $this->f,
			't' => $this->t,
			'e' => $this->e,

		];
	}

	/**
	 * @param float|int|numeric-string $number
	 *
	 * @return float|int|numeric-string
	 */
	private function expand_compact_decimal_exponent(float|int|string $number): float|int|string
	{
		return Number::expand_compact_decimal_exponent($number, $this->e);
	}
}
