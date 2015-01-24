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

use ICanBoogie\Core;

class Hooks
{
	/*
	 * Prototypes
	 */

	/**
	 * Returns a provider chain with the following providers: {@link WebProvider},
	 * {@link FileProvider}, and {@link RunTimeProvider}. The {@link FileProvider} is created with
	 * `REPOSITORY/cldr` as cache directory.
	 *
	 * @return ProviderInterface
	 */
	static public function get_cldr_provider()
	{
		static $provider;

		if (!$provider)
		{
			$provider = new RunTimeProvider(new FileProvider(new WebProvider, \ICanBoogie\REPOSITORY . 'cldr'));
		}

		return $provider;
	}

	/**
	 * Returns a {@link Repository} instance created with `$app->cldr_provider`.
	 *
	 * @param Core $app
	 *
	 * @return Repository
	 */
	static public function get_cldr(Core $app)
	{
		static $cldr;

		if (!$cldr)
		{
			$cldr = new Repository($app->cldr_provider);
		}

		return $cldr;
	}

	static private $locale;

	/**
	 * Returns the locale used by the application.
	 *
	 * @param Core $app
	 *
	 * @return Locale
	 */
	static public function get_locale(Core $app)
	{
		$locale = self::$locale;

		if (!($locale instanceof Locale))
		{
			$locale = self::$locale = $app->cldr[$locale];
		}

		return $locale;
	}

	/**
	 * Sets the locale used by the application.
	 *
	 * @param Core $app
	 * @param Locale|string $locale
	 */
	static public function set_locale(Core $app, $locale)
	{
		self::$locale = $locale;
	}
}
