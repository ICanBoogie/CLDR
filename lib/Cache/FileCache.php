<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use function dirname;
use function fclose;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function flock;
use function fopen;
use function is_writable;
use function json_decode;
use function json_encode;
use function mt_rand;
use function rename;
use function restore_error_handler;
use function rtrim;
use function set_error_handler;
use function str_replace;
use function strpos;
use function uniqid;
use function unlink;

/**
 * A storage using the file system.
 */
class FileCache implements Cache
{
	static private $release_after;

	/**
	 * Absolute path to the storage directory.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Constructor.
	 *
	 * @param string $path Absolute path to the storage directory.
	 */
	public function __construct($path)
	{
		$this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		if (self::$release_after === null)
		{
			self::$release_after = strpos(PHP_OS, 'WIN') === 0 ? false : true;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function get($path)
	{
		$pathname = $this->format_pathname($path);

		if (!file_exists($pathname))
		{
			return null;
		}

		return $this->read($pathname);
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function set($path, array $data)
	{
		$this->assert_writable();

		$pathname = $this->format_pathname($path);

		set_error_handler(function() {});

		try
		{
			$this->safe_set($pathname, $data);
		}
		catch (\Exception $e)
		{
			throw $e;
		}
		finally
		{
			restore_error_handler();
		}
	}

	/**
	 * Formats a path into an absolute pathname.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private function format_pathname($path)
	{
		return $this->path . str_replace('/', '--', $path);
	}

	/**
	 * @param string $pathname
	 *
	 * @return array
	 */
	private function read($pathname)
	{
		return json_decode(file_get_contents($pathname), true);
	}

	/**
	 * @param string $pathname
	 * @param array $data
	 */
	private function write($pathname, array $data)
	{
		file_put_contents($pathname, json_encode($data));
	}

	/**
	 * Safely set the value.
	 *
	 * @param $pathname
	 * @param $data
	 *
	 * @throws \Exception if an error occurs.
	 */
	private function safe_set($pathname, $data)
	{
		$dir = dirname($pathname);
		$uniqid = uniqid(mt_rand(), true);
		$tmp_pathname = $dir . '/var-' . $uniqid;
		$garbage_pathname = $dir . '/garbage-var-' . $uniqid;

		#
		# We lock the file create/update, but we write the data in a temporary file, which is then
		# renamed once the data is written.
		#

		$fh = fopen($pathname, 'a+');

		if (!$fh)
		{
			throw new \Exception("Unable to open $pathname.");
		}

		if (self::$release_after && !flock($fh, LOCK_EX))
		{
			throw new \Exception("Unable to get to exclusive lock on $pathname.");
		}

		$this->write($tmp_pathname, $data);

		#
		# Windows, this is for you
		#
		if (!self::$release_after)
		{
			fclose($fh);
		}

		if (!rename($pathname, $garbage_pathname))
		{
			throw new \Exception("Unable to rename $pathname as $garbage_pathname.");
		}

		if (!rename($tmp_pathname, $pathname))
		{
			throw new \Exception("Unable to rename $tmp_pathname as $pathname.");
		}

		if (!unlink($garbage_pathname))
		{
			throw new \Exception("Unable to delete $garbage_pathname.");
		}

		#
		# Unix, this is for you
		#
		if (self::$release_after)
		{
			flock($fh, LOCK_UN);
			fclose($fh);
		}
	}

	/**
	 * Checks whether the storage directory is writable.
	 *
	 * @throws \Exception when the storage directory is not writable.
	 */
	private function assert_writable()
	{
		if (!is_writable($path = $this->path))
		{
			throw new \Exception("The directory $path is not writable.");
		}
	}
}
