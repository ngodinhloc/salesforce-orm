<?php

namespace Salesforce\Cache\Engines;

use Salesforce\Cache\CacheEngine;
use Salesforce\Cache\CacheEngineInterface;
use Salesforce\Cache\Exception\FileCacheException;

/**
 * Class FileCache: store and retrieve cache in text file
 *
 * @package jennifer\Cache
 */
class FileCache extends CacheEngine implements CacheEngineInterface
{
    /**
     * Write to cache file
     *
     * @param string $key
     * @param mixed $data
     * @return bool
     * @throws \Salesforce\Cache\Exception\FileCacheException
     */
    public function writeCache(string $key = null, array $data = null)
    {
        $file = $this->cacheDir . $this->createKey($key);
        $array = ['time' => time(), 'data' => $data];
        $json = json_encode($array);
        if ($json) {
            try {
                $result = file_put_contents($file, $json);
                if ($result) {
                    return true;
                }
            } catch (\Exception $exception) {
                throw new FileCacheException(FileCacheException::ERROR_FAILED_TO_PUT_CONTENT . $exception->getMessage());
            }
        }

        return false;
    }

    /**
     * Get cache in original data format
     *
     * @param string $key
     * @return mixed|null
     * @throws \Salesforce\Cache\Exception\FileCacheException
     */
    public function getCache(string $key = null)
    {
        $file = $this->cacheDir . $this->createKey($key);
        if (file_exists($file)) {
            try {
                $content = file_get_contents($file);
                if ($content) {
                    $arr = json_decode($content, true);
                    $time = $arr['time'];
                    $data = $arr['data'];
                    if ($this->cacheTime >= time() - $time) {
                        return $data;
                    }
                }
            } catch (\Exception $exception) {
                throw new FileCacheException(FileCacheException::ERROR_FAILED_TO_GET_CONTENT . $exception->getMessage());
            }
        }

        return null;
    }

    /**
     * Create cache key
     *
     * @param string $key
     * @return string
     */
    public function createKey(string $key = null)
    {
        return md5($key);
    }
}
