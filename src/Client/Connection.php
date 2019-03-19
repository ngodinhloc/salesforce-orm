<?php
namespace Salesforce\Client;

class Connection
{
    /** @var Config */
    protected $config;
    /** @var Client */
    protected $client;

    /**
     * Connection constructor.
     *
     * @param array $configuration
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     * ]
     * @throws \Salesforce\Client\Exception\ConfigException
     * @throws \EventFarm\Restforce\RestforceException
     */
    public function __construct($configuration = [])
    {
        $this->config = new Config($configuration);
        $this->client = new Client($this->config);
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
