<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Plurals\Rule;
use ICanBoogie\CLDR\Plurals\Samples;

/**
 * Representation of plurals
 */
class Plurals extends \ArrayObject
{
	const COUNT_ZERO = 'zero';
	const COUNT_ONE = 'one';
	const COUNT_TWO = 'two';
	const COUNT_FEW = 'few';
	const COUNT_MANY = 'many';
	const COUNT_OTHER = 'other';

	const RULE_COUNT_PREFIX = 'pluralRule-count-';

	/**
	 * @var Rule[][]
	 */
	private $rules = [];

	/**
	 * @var Samples[][]
	 */
	private $samples = [];

	/**
	 * @param number $number
	 * @param string $locale
	 *
	 * @return string One of `COUNT_*`.
	 */
	public function rule_for($number, $locale)
	{
		foreach ($this->rule_instances_for($locale) as $count => $rule)
		{
			if ($rule->validate($number))
			{
				return $count;
			}
		}

		return self::COUNT_OTHER; // @codeCoverageIgnore
	}

	/**
	 * @param string $locale
	 *
	 * @return string[]
	 */
	public function rules_for($locale)
	{
		return array_keys($this->rule_instances_for($locale));
	}

	/**
	 * @param string $locale
	 *
	 * @return Samples[]
	 */
	public function samples_for($locale)
	{
		$samples = &$this->samples[$locale];

		return $samples ?: $samples = $this->create_samples_for($locale);
	}

	/**
	 * @param string $locale
	 *
	 * @return Rule[]
	 */
	private function rule_instances_for($locale)
	{
		$rules = &$this->rules[$locale];

		return $rules ?: $rules = $this->create_rules_for($locale);
	}

	/**
	 * @param string $locale
	 *
	 * @return Rule[]
	 */
	private function create_rules_for($locale)
	{
		$rules = [];
		$prefix_length = strlen(self::RULE_COUNT_PREFIX);

		foreach ($this[$locale] as $count => $rule_string)
		{
			$count = substr($count, $prefix_length);
			$rules[$count] = Rule::from($this->extract_rule($rule_string));
		}

		return $rules;
	}

	/**
	 * @param string $rule_string
	 *
	 * @return string
	 */
	private function extract_rule($rule_string)
	{
		$rule = explode('@', $rule_string, 2);
		$rule = array_shift($rule);
		$rule = trim($rule);

		return $rule;
	}

	/**
	 * @param string $locale
	 *
	 * @return Samples[]
	 */
	private function create_samples_for($locale)
	{
		$samples = [];
		$prefix_length = strlen(self::RULE_COUNT_PREFIX);

		foreach ($this[$locale] as $count => $rule_string)
		{
			$count = substr($count, $prefix_length);
			$samples[$count] = Samples::from($this->extract_samples($rule_string));
		}

		return $samples;
	}

	/**
	 * @param string $rule_string
	 *
	 * @return string
	 */
	private function extract_samples($rule_string)
	{
		return substr($rule_string, strpos($rule_string, '@'));
	}
}
