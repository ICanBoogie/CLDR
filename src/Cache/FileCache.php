<?php

namespace ICanBoogie\CLDR\Cache;

use Exception;
use ICanBoogie\CLDR\Cache;
use Throwable;

use function dirname;
use function fclose;
use function file_exists;
use function file_put_contents;
use function flock;
use function fopen;
use function is_writable;
use function mt_rand;
use function rename;
use function restore_error_handler;
use function rtrim;
use function set_error_handler;
use function str_replace;
use function uniqid;
use function unlink;

/**
 * A storage using the file system.
 */
final class FileCache implements Cache
{
    /**
     * Recommended name for the cache directory.
     */
    public const RECOMMENDED_DIR = '.cldr-cache';

    private static bool $release_after;

    /**
     * Absolute path to the storage directory.
     */
    private string $path;

    /**
     * @param string $path Absolute path to the storage directory.
     */
    public function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        self::$release_after ??= !(str_starts_with(PHP_OS, 'WIN'));
    }

    public function get(string $path): ?array
    {
        $pathname = $this->format_absolute_pathname($path);

        if (!file_exists($pathname)) {
            return null;
        }

        return $this->read($pathname);
    }

    /**
     * @throws Throwable
     */
    public function set(string $path, array $data): void
    {
        $this->assert_writable();

        $pathname = $this->format_absolute_pathname($path);

        // @phpstan-ignore-next-line
        set_error_handler(fn() => null);

        try {
            $this->safe_set($pathname, $data);
        } finally {
            restore_error_handler();
        }
    }

    private function format_absolute_pathname(string $path): string
    {
        return $this->path . str_replace('/', '--', $path) . '.php';
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function read(string $pathname): array
    {
        return require $pathname;
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function write(string $pathname, array $data): void
    {
        $var = var_export($data, true);
        $content = <<<PHP
        <?php return $var;
        PHP;

        file_put_contents($pathname, $content);
    }

    /**
     * Safely set the value.
     *
     * @throws Exception
     *
     * @phpstan-ignore-next-line
     */
    private function safe_set(string $pathname, array $data): void
    {
        $dir = dirname($pathname);
        $uniqid = uniqid((string)mt_rand(), true);
        $tmp_pathname = $dir . '/var-' . $uniqid;
        $garbage_pathname = $dir . '/garbage-var-' . $uniqid;

        #
        # We lock the file create/update, but we write the data in a temporary file, which is then
        # renamed once the data is written.
        #

        $fh = fopen($pathname, 'a+');

        if (!$fh) {
            throw new Exception("Unable to open $pathname.");
        }

        if (self::$release_after && !flock($fh, LOCK_EX)) {
            throw new Exception("Unable to get to exclusive lock on $pathname.");
        }

        $this->write($tmp_pathname, $data);

        #
        # Windows, this is for you
        #
        if (!self::$release_after) {
            fclose($fh);
        }

        if (!rename($pathname, $garbage_pathname)) {
            throw new Exception("Unable to rename $pathname as $garbage_pathname.");
        }

        if (!rename($tmp_pathname, $pathname)) {
            throw new Exception("Unable to rename $tmp_pathname as $pathname.");
        }

        if (!unlink($garbage_pathname)) {
            throw new Exception("Unable to delete $garbage_pathname.");
        }

        #
        # Unix, this is for you
        #
        if (self::$release_after) {
            flock($fh, LOCK_UN);
            fclose($fh);
        }
    }

    /**
     * Checks whether the storage directory is writable.
     *
     * @throws Exception when the storage directory is not writable.
     */
    private function assert_writable(): void
    {
        $path = $this->path;

        if (!is_writable($path)) {
            throw new Exception("The directory $path is not writable.");
        }
    }
}
