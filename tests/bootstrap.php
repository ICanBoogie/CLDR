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

require __DIR__ . '/../vendor/autoload.php';

if (!file_exists(__DIR__ . '/repository'))
{
	mkdir(__DIR__ . '/repository');
}

/**
 * @return Provider
 */
function create_provider_stack()
{
	return new RunTimeProvider(new FileProvider(new WebProvider, __DIR__ . '/repository'));
}

/**
 * @return Repository
 */
function get_repository()
{
	static $repository;

	if (!$repository)
	{
		$repository = new Repository(create_provider_stack());
	}

	return $repository;
}

date_default_timezone_set('Europe/Madrid');
