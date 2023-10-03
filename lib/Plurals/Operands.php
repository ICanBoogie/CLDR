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
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Operands
 */
final class Operands
{
	/**
	 * @param float|int|numeric-string $number
	 */
	static public function from(float|int|string $number): self
	{
		return OperandsCache::get($number, static fn(): self => new self($number));
	}

	public readonly int|float $n;
	public readonly int $i;
	public readonly int $v;
	public readonly int $w;
	public readonly int $f;
	public readonly int $t;
	public readonly int $e;

	/**
	 * @param float|int|numeric-string $number
	 */
	private function __construct(float|int|string $number)
	{
		$e = 0;
		$number = Number::expand_compact_decimal_exponent($number, $e);

		[ $integer, $fractional ] = Number::parse($number);

		$n = abs($number);

		if ($fractional === null || (int) $fractional === 0)
		{
			$n = (int) $n;
		}

		$this->n = $n;
		$this->i = $integer;
		$this->e = $e;

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
}
