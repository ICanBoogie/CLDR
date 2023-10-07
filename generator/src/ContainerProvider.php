<?php

namespace ICanBoogie\CLDR\Generator;

use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;
use ICanBoogie\CLDR\Generator\Command\LocaleIdCommand;
use ICanBoogie\CLDR\Generator\Command\SequenceCompanionCommand;
use ICanBoogie\CLDR\Generator\Command\UnitsCompanionCommand;
use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\Repository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerProvider
{
	private const COMMANDS = [
		LocaleIdCommand::class,
        SequenceCompanionCommand::class,
        UnitsCompanionCommand::class,
	];

	public static function provide_container(): ContainerInterface
	{
		$container = new ContainerBuilder();
		$container->addCompilerPass(new AddConsoleCommandPass());

		$container->register(Repository::class)
			->setFactory([ self::class, 'repository_factory' ]);

		foreach (self::COMMANDS as $command) {
			$container
				->register( $command)
				->addTag('console.command')
				->setAutowired(true);
		}

		$container->compile();

		return $container;
	}

	public static function repository_factory(): Repository
	{
		$provider = new Provider\CachedProvider(
			new Provider\WebProvider(),
			new CacheCollection([
				new RuntimeCache(),
				new FileCache(CACHE)
			])
		);

		return new Repository($provider);
	}
}
