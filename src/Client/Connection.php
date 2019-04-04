<?php
namespace Salesforce\Client;

use Psr\Log\LoggerInterface;
use Salesforce\Cache\CacheEngineFactory;

class Connection
{
    /** @var Config */
    protected $config;
    /** @var Client */
    protected $client;

    /**
     * Connection constructor.
     *
     * @param array $clientConfig
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     * ]
     * @param array $cacheConfig
     * @param \Psr\Log\LoggerInterface|null $logger
     * @throws Exception\ConfigException
     * @throws \EventFarm\Restforce\RestforceException
     * @throws \Salesforce\Cache\Exception\CacheException
     */
    public function __construct($clientConfig = [], $cacheConfig = [], LoggerInterface $logger = null)
    {
        $cache = empty($cacheConfig) ? null : CacheEngineFactory::createCacheEngine($cacheConfig);
        $this->config = new Config($clientConfig);
        $this->client = new Client($this->config, $cache, $logger);
    }

    /**
     * @return \Salesforce\Client\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Salesforce\Client\Config $config
     * @return \Salesforce\Client\Connection
     */
    public function setConfig(Config $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Salesforce\Client\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \Salesforce\Client\Client $client
     * @return \Salesforce\Client\Connection
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

}
