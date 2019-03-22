<?php
namespace Salesforce\Cache;

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
     */
    public function __construct(string $cacheDir = null, $cacheTime = 36000)
    {
        $this->cacheDir = $cacheDir;
        $this->cacheTime = $cacheTime;
    }
}
