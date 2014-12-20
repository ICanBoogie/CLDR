<?php

namespace ICanBoogie\CLDR;

/**
 * A trait for stackable providers which also implement {@link CacheInterface}.
 *
 * @package ICanBoogie\CLDR
 */
trait ProviderStackTrait
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
