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
use function ICanBoogie\iterable_every;
use function ICanBoogie\iterable_some;

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-25-numbers.html#Language_Plural_Rules
 */
final class Rule
{
	static public function from(string $rule): Rule
	{
		return RuleCache::get(
			$rule,
			static fn(): Rule => new self(self::parse_rule($rule))
		);
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
		return array_map(
			static fn(string $rule): array => explode(' and ', $rule),
			explode(' or ', $rule)
		);
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
		return iterable_some(
			$or_relations,
			fn($and_relations) => $this->validate_and($operands, $and_relations)
		);
	}

	/**
	 * @param Relation[] $and_relations
	 */
	public function validate_and(Operands $operands, iterable $and_relations): bool
	{
		return iterable_every(
			$and_relations,
			fn(Relation $relation) => $relation->evaluate($operands)
		);
	}
}
