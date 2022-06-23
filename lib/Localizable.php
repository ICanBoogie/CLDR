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

/**
 * An interface for classes whose instances can be localized.
 */
interface Localizable
{
	/**
	 * Localize the source object.
	 *
	 * @param object $source
	 * @param array<string, mixed> $options
	 */
	static public function localize($source, Locale $locale, array $options = []): LocalizedObject;
}
