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

use function array_map;
use function array_walk_recursive;
use function explode;
use function in_array;

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-25-numbers.html#Language_Plural_Rules
 */
final class Rule
{
	/**
	 * @var Rule[]
	 */
	static private $instances = [];

	static public function from(string $rule): Rule
	{
		$instance = &self::$instances[$rule];

		return $instance ?? $instance = new self(self::parse_rule($rule));
	}

	/**
	 * @return Relation[][]
	 */
	static private function parse_rule(string $rules): array
	{
		$rules = self::extract_rule($rules);

		array_walk_recursive($rules, function (&$rule) {

			$rule = Relation::from($rule);

		});

		return $rules;
	}

	/**
	 * @return array An array of ands[ors][]
	 */
	static private function extract_rule(string $rule): array
	{
		return array_map(function ($rule) {

			return explode(' and ', $rule);

		}, explode(' or ', $rule));
	}

	/**
	 * @var Relation[][]
	 */
	private $relations;

	/**
	 * @param Relation[][] $relations
	 */
	private function __construct(array $relations)
	{
		$this->relations = $relations;
	}

	/**
	 * Whether a number matches the rules.
	 *
	 * @param int|float $number
	 */
	public function validate($number): bool
	{
		$relations = $this->relations;
		$operands = Operands::from($number);

		// replace the relations with their evaluations
		array_walk_recursive($relations, function (Relation &$relation) use ($operands) {

			$relation = $relation->evaluate($operands);

		});

		$relations = array_map(function ($evaluations) {

			return !in_array(false, $evaluations);

		}, $relations);

		return in_array(true, $relations);
	}
}
