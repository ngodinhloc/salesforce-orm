<?php
namespace Salesforce\Cache;

interface CacheEngineInterface
{
    /**
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    public function writeCache(string $key = null, $data = null);

    /**
     * @param string|null $key
     * @return mixed|null
     */
    public function getCache(string $key = null);

    /**
     * Create cache key
     *
     * @param string $key
     * @return string
     */
    public function createKey(string $key = null);
}
