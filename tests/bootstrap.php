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

$autoload = require __DIR__ . '/../vendor/autoload.php';
$autoload->addPsr4(__NAMESPACE__ . '\\', __DIR__);

if (!file_exists(__DIR__ . '/repository'))
{
	mkdir(__DIR__ . '/repository');
}

/**
 * @return Provider
 */
function create_provider_collection()
{
	return new ProviderCollection([

		new RunTimeProvider,
		new FileProvider(__DIR__ . '/repository'),
		new WebProvider

	]);
}

/**
 * @return Repository
 */
function get_repository()
{
	static $repository;

	if (!$repository)
	{
		$repository = new Repository(create_provider_collection());
	}

	return $repository;
}

date_default_timezone_set('Europe/Madrid');
