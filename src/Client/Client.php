<?php
namespace Salesforce\Client;

use EventFarm\Restforce\Restforce;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Salesforce\Cache\CacheEngineInterface;
use Salesforce\Client\Exception\ClientException;

/**
 * Class Client
 *
 * @package Salesforce\Client
 */
class Client
{
    /** @var Restforce */
    protected $restforce;

    /** @var Config */
    protected $config;

    /** @var CacheEngineInterface */
    protected $cache;

    /**
     * Client constructor.
     *
     * @param \Salesforce\Client\Config|null $config config
     * @param \Salesforce\Cache\CacheEngineInterface|null $cache
     * @throws \EventFarm\Restforce\RestforceException
     */
    public function __construct(Config $config = null, CacheEngineInterface $cache = null)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->restforce = new Restforce(
            $this->config->getClientId(),
            $this->config->getClientSecret(),
            $this->config->getPath(),
            null,
            $this->config->getUsername(),
            $this->config->getPassword(),
            $this->config->getApiVersion()
        );
    }

    /**
     * @param string $sObject object name
     * @param array $data associative array to send to salesforce.
     * @return string
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function createObject(string $sObject = null, array $data = null)
    {
        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->create($sObject, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_CREATE_OBJECT . $e->getMessage());
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string $sObject object
     * @param string $sObjectId id to update
     * @param array $data data in associate array format
     * @return bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function updateObject(string $sObject = null, string $sObjectId = null, array $data = null)
    {
        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->update($sObject, $sObjectId, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_UPDATE_OBJECT . $e->getMessage());
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string $sObject salesforce object name
     * @param string $sObjectId id
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findObject(string $sObject = null, string $sObjectId = null)
    {
        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        if ($this->cache) {
            $cache = $this->cache->getCache($this->cache->createKey($sObject . $sObjectId));
            if ($cache !== null) {
                return $cache;
            }
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->find($sObject, $sObjectId);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_FIND_OBJECT . $e->getMessage());
        }

        $result = (new Result($response))->get();

        if ($this->cache) {
            $this->cache->writeCache($this->cache->createKey($sObject . $sObjectId), $result);
        }

        return $result;
    }

    /**
     * @param string $query query string
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function query(string $query = null)
    {
        if (empty($query)) {
            throw new ClientException(ClientException::MSG_QUERY_MISSING);
        }

        if ($this->cache) {
            $cache = $this->cache->getCache($this->cache->createKey($query));
            if ($cache !== null) {
                return $cache;
            }
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->query($query);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_FIND_OBJECT . $e->getMessage());
        }

        $result = (new Result($response))->get();

        if ($this->cache) {
            $this->cache->writeCache($this->cache->createKey($query), $result);
        }

        return $result;
    }

    /**
     * @return Restforce
     */
    public function getRestforce()
    {
        return $this->restforce;
    }

    /**
     * @param Restforce $restforce
     * @return \Salesforce\Client\Client
     */
    public function setRestforce(Restforce $restforce = null)
    {
        $this->restforce = $restforce;

        return $this;
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
     * @return \Salesforce\Client\Client
     */
    public function setConfig(Config $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return CacheEngineInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param \Salesforce\Cache\CacheEngineInterface $cache
     * @return \Salesforce\Client\Client
     */
    public function setCache(CacheEngineInterface $cache = null)
    {
        $this->cache = $cache;

        return $this;
    }
}
