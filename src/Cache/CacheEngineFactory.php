<?php
namespace Salesforce\Cache;

use Salesforce\Cache\Engines\FileCache;
use Salesforce\Cache\Exception\CacheException;

class CacheEngineFactory
{
    const REQUIRED_CONFIGURATION_DATA = ['engine', 'dir', 'time'];

    /**
     * @param string $config
     * @return \Salesforce\Cache\CacheEngineInterface|null
     * @throws \Salesforce\Cache\Exception\CacheException
     */
    public static function createCacheEngine($config = null)
    {
        if (!isset($config['engine']) || !isset($config['dir']) || !isset($config['time'])) {
            throw new CacheException(CacheException::MSG_MISSING_CACHE_CONFIGURATION . implode(self::REQUIRED_CONFIGURATION_DATA));
        }
        $engine = null;
        switch ($config['engine']) {
            case "file":
                $engine = new FileCache($config['dir'], $config['time']);
                break;
        }

        return $engine;
    }
}
