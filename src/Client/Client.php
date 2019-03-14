<?php
namespace Salesforce\Client;

use EventFarm\Restforce\Restforce;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Salesforce\Client\Exception\ClientException;
use Salesforce\ORM\Query\Result;

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

    /**
     * Client constructor.
     *
     * @param Config|null $config config
     * @throws \EventFarm\Restforce\RestforceException
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
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
     * @return mixed
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\ORM\Exception\ResultException
     */
    public function createObject(string $sObject, array $data): array
    {
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
     * @param string $objectId id to update
     * @param array $data data in associate array format
     * @return mixed
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\ORM\Exception\ResultException
     */
    public function updateObject(string $sObject, string $objectId, array $data): int
    {
        if (empty($objectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->update($sObject, $objectId, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_UPDATE_OBJECT . $e->getMessage());
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string $sObject salesforce object name
     * @param string $sObjectId id
     * @return mixed
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\ORM\Exception\ResultException
     */
    public function findObject($sObject, $sObjectId): array
    {
        if (empty($objectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->find($sObject, $sObjectId);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_FIND_OBJECT . $e->getMessage());
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @return Restforce
     */
    public function getRestforce(): Restforce
    {
        return $this->restforce;
    }

    /**
     * @param Restforce $restforce
     * @return Client
     */
    public function setRestforce(Restforce $restforce): Client
    {
        $this->restforce = $restforce;

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return Client
     */
    public function setConfig(Config $config): Client
    {
        $this->config = $config;

        return $this;
    }
}
