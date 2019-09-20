<?php
namespace SalesforceTest\Cache\Engine;

use PHPUnit\Framework\TestCase;
use Salesforce\Cache\Exception\CacheException;
use Salesforce\Cache\Exception\FileCacheException;
use Salesforce\Cache\CacheEngineFactory;

class FileCacheTest extends TestCase
{
    /** @var \Salesforce\Cache\Engines\FileCache cache */
    private $cache;
    private $config;

    public function setUp()
    {

        $this->config = [
            'engine' => 'file',
            'dir' => __DIR__,
            'time' => 36000
        ];

        $this->cache = CacheEngineFactory::createCacheEngine($this->config);
        parent::setUp();
    }

    public function testWriteCache()
    {
        $result = $this->cache->writeCache('key', ['just some random data']);
        $this->assertTrue($result);
    }

    public function testGetCache()
    {
        $result = $this->cache->getCache('key');
        $this->assertContains($result[0], 'just some random data');
    }

    public function testcreateCacheEngineInvalidDir()
    {
        $this->config['dir'] = 'INVALID/PATH';

        try {
            $this->cache = CacheEngineFactory::createCacheEngine($this->config);
        } catch (CacheException $e) {

            $this->assertSame($e->getMessage(), CacheException::MSG_INVALID_CACHE_DIR . $this->config['dir']);
        }
    }

    public function testcreateCacheEngineMissingConfig()
    {
        $this->config = [
            'engine' => 'file',
            'dir' => __DIR__,
        ];
        try {
            $this->cache = CacheEngineFactory::createCacheEngine($this->config);
        } catch (CacheException $e) {

            $this->assertSame($e->getMessage(), CacheException::MSG_MISSING_CACHE_CONFIGURATION . implode(CacheEngineFactory::REQUIRED_CONFIGURATION_DATA));
        }
    }

    public function testWriteCacheWithoutFilePermissions()
    {
        chmod( $this->config['dir'] . $this->cache->createKey('key'),0000);
        try {
            $this->cache->writeCache('key', ['just some random data']);
        } catch (FileCacheException $e) {

            $this->assertContains(FileCacheException::ERROR_FAILED_TO_PUT_CONTENT, $e->getMessage());
        }

        chmod( $this->config['dir'] . $this->cache->createKey('key'),0755);
    }

    public function testGetCacheCacheWithoutFilePermissions()
    {
        chmod( $this->config['dir'] . $this->cache->createKey('key'),0000);
        try {
            $this->cache->getCache('key');
        } catch (FileCacheException $e) {

            $this->assertContains(FileCacheException::ERROR_FAILED_TO_GET_CONTENT, $e->getMessage());
        }

        chmod( $this->config['dir'] . $this->cache->createKey('key'),0755);
    }
}
