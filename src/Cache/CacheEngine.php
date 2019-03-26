<?php
namespace Salesforce\Cache;

use Salesforce\Cache\Exception\CacheException;

/**
 * Class CacheEngine
 *
 * @package Salesforce\Cache
 */
abstract class CacheEngine
{
    protected $cacheDir;
    protected $cacheTime;

    /**
     * CacheEngine constructor.
     *
     * @param string|null $cacheDir
     * @param int $cacheTime
     * @throws \Salesforce\Cache\Exception\CacheException
     */
    public function __construct(string $cacheDir = null, int $cacheTime = 36000)
    {
        if (!is_dir($cacheDir)) {
            throw new CacheException(CacheException::MSG_INVALID_CACHE_DIR . $cacheDir);
        }
        $this->cacheDir = $cacheDir;
        $this->cacheTime = $cacheTime;
    }
}
