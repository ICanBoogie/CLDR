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

function get_repository()
{
	static $repository;

	if (!$repository)
	{
		$provider = new Provider(new RunTimeCache(new FileCache(__DIR__ . '/repository')), new Retriever);
		$repository = new Repository($provider);
	}

	return $repository;
}