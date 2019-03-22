<?php
namespace Salesforce\Client;

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
     * @throws \Salesforce\Client\Exception\ConfigException
     * @throws \EventFarm\Restforce\RestforceException
     * @throws \Salesforce\Cache\Exception\CacheException
     */
    public function __construct($clientConfig = [], $cacheConfig = [])
    {
        $cache = empty($cacheConfig) ? null : CacheEngineFactory::createCacheEngine($cacheConfig);
        $this->config = new Config($clientConfig);
        $this->client = new Client($this->config, $cache);
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
    public function setConfig(Config $config)
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
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

}
