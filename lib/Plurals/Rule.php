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

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-25-numbers.html#Language_Plural_Rules
 */
final class Rule
{
	/**
	 * @var array
	 */
	static private $instances = [];

	/**
	 * @param string $rule
	 *
	 * @return Rule
	 */
	static public function from($rule)
	{
		$instance = &self::$instances[$rule];

		return $instance ?: $instance = new static(self::parse_rule($rule));
	}

	/**
	 * @param string $rules
	 *
	 * @return Relation[][]
	 */
	static private function parse_rule($rules)
	{
		$rules = self::extract_rule($rules);

		array_walk_recursive($rules, function (&$rule) {

			$rule = Relation::from($rule);

		});

		return $rules;
	}

	/**
	 * @param string $rule
	 *
	 * @return array An array of ands[ors][]
	 */
	static private function extract_rule($rule)
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
	 * @param number $number
	 *
	 * @return bool `true` if the number matches the rules, `false` otherwise.
	 */
	public function validate($number)
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
