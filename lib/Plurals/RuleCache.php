<?php

namespace ICanBoogie\CLDR\Plurals;

/**
 * @internal
 */
final class RuleCache
{
	/**
	 * @var array<string, Rule>
	 *     Where _key_ is a rule statement and _value_ a {@link Rule}.
	 */
	static private array $instances = [];

	/**
	 * @param callable():Rule $new
	 */
	static public function get(string $rule, callable $new): Rule
	{
		return self::$instances[$rule] ??= $new();
	}
}
