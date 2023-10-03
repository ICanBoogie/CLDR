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

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-25-numbers.html#Language_Plural_Rules
 */
final class Rule
{
	static public function from(string $rule): Rule
	{
		return RuleCache::get($rule, static function () use ($rule): Rule {
			return new self(self::parse_rule($rule));
		});
	}

	/**
	 * @return array<Relation[]>
	 *     An array of 'OR' relations, where _value_ is an array of 'AND' relations.
	 */
	static private function parse_rule(string $rules): array
	{
		$relations = self::extract_relations($rules);
		array_walk_recursive($relations, function (string &$relation): void {
			$relation = Relation::from($relation);
		});

		/** @var array<Relation[]> */
		return $relations;
	}

	/**
	 * @return array<string[]>
	 *      An array of 'OR' relations, where _value_ is an array of 'AND' relations.
	 */
	static private function extract_relations(string $rule): array
	{
		return array_map(function ($rule) {
			return explode(' and ', $rule);
		}, explode(' or ', $rule));
	}

	/**
	 * @param Relation[][] $relations
	 */
	private function __construct(
		private readonly array $relations
	) {
	}

	/**
	 * Whether a number matches the rule.
	 *
	 * @param float|int|numeric-string $number
	 */
	public function validate(float|int|string $number): bool
	{
		$operands = Operands::from($number);

		return $this->validate_or($operands, $this->relations);
	}

	/**
	 * @param Relation[][] $or_relations
	 */
	public function validate_or(Operands $operands, iterable $or_relations): bool
	{
		foreach ($or_relations as $and_relations) {
			if ($this->validate_and($operands, $and_relations))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param Relation[] $and_relations
	 */
	public function validate_and(Operands $operands, iterable $and_relations): bool
	{
		foreach ($and_relations as $relation)
		{
			if (!$relation->evaluate($operands))
			{
				return false;
			}
		}

		return true;
	}
}
