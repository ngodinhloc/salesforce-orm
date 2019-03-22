<?php
namespace Salesforce\Cache;

interface CacheEngineInterface
{
    /**
     * @param $key
     * @param $data
     * @return bool
     */
    public function writeCache(string $key = null, array $data = null);

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
