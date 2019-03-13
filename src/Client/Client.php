<?php
namespace Salesforce\Client;

use EventFarm\Restforce\Restforce;
use EventFarm\Restforce\RestforceInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
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
     * call client
     *
     * @param string $method Resforce method name
     * @param array $arguments passed onto restforce
     * @return ResponseInterface
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function __call(string $method, array $arguments): ResponseInterface
    {
        if (!method_exists($this->getRestforce(), $method)) {
            throw new ClientException(ClientException::MSG_METHOD_NOT_EXISTS . $method);
        }
        $result = call_user_func_array([$this->getRestforce(), $method], $arguments);

        return $result;
    }

    /**
     * @param string $sObject object name
     * @param array $data associative array to send to salesforce.
     * @return array
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function createObject(string $sObject, array $data): array
    {
        try {
            /** @var ResponseInterface $response */
            $response = $this->create($sObject, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_CREATE_OBJECT . $e->getMessage());
        }
        $content = json_decode($response->getBody()->getContents(), true);

        if (empty($content['success'])) {
            throw new ClientException(ClientException::MSG_FAILED_TO_CREATE_OBJECT . $sObject);
        }

        return $content;
    }

    /**
     * @param string $sObject object
     * @param string $salesforceId id to update
     * @param array $data data in associate array format
     * @return int
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function updateObject(string $sObject, string $salesforceId, array $data): int
    {
        if (empty($salesforceId)) {
            return ResponseCodes::HTTP_BAD_REQUEST;
        }

        try {
            /** @var ResponseInterface $response */
            $response = $this->update($sObject, $salesforceId, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_UPDATE_OBJECT . $e->getMessage());
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode !== ResponseCodes::HTTP_NO_CONTENT) {
            throw new ClientException(ClientException::MSG_FAILED_TO_UPDATE_OBJECT . $sObject);
        }

        return $statusCode;
    }

    /**
     * @param string $sObject salesforce object name
     * @param string $sObjectId id
     * @return array
     */
    public function getObject($sObject, $sObjectId): array
    {
        return json_decode($this->restforce->find($sObject, $sObjectId)->getBody()->getContents(), true);
    }

    /**
     * RestforceInterface getter
     *
     * @return RestforceInterface
     */
    public function getRestforce(): RestforceInterface
    {
        return $this->restforce;
    }
}
