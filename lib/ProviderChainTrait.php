<?php

namespace ICanBoogie\CLDR;

/**
 * A trait for chained providers which also implement {@link CacheInterface}.
 *
 * @package ICanBoogie\CLDR
 */
trait ProviderChainTrait
{
	/**
	 * @var ProviderInterface
	 */
	protected $provider;

	/**
	 * @param ProviderInterface $provider Next provider.
	 */
	public function __construct(ProviderInterface $provider)
	{
		$this->provider = $provider;
	}

	abstract public function exists($path);
	abstract public function retrieve($path);
	abstract public function store($path, $data);

	/**
	 * The section path, following the pattern "<identity>/<section>".
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function provide($path)
	{
		if ($this->exists($path))
		{
			return $this->retrieve($path);
		}

		$data = $this->provider->provide($path);
		$this->store($path, $data);

		return $data;
	}
}

