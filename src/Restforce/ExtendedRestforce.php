<?php

namespace Salesforce\Restforce;

use EventFarm\Restforce\Rest\OAuthAccessToken;
use EventFarm\Restforce\Rest\OAuthRestClient;
use EventFarm\Restforce\Rest\RestClientInterface;
use EventFarm\Restforce\RestforceException;
use Psr\Http\Message\ResponseInterface;

class ExtendedRestforce implements ExtendedRestforceInterface
{
    const USER_INFO_ENDPOINT = 'RESOURCE_OWNER';
    const DEFAULT_API_VERSION = 'v38.0';
    const DEFAULT_JOB_BASE_URI = 'jobs/';

    /** @var string */
    protected $clientId;
    /** @var string */
    protected $clientSecret;
    /** @var null|string */
    protected $username;
    /** @var null|string */
    protected $password;
    /** @var OAuthAccessToken|null */
    protected $accessToken;
    /** @var string */
    protected $apiVersion;
    /** @var OAuthRestClient|null */
    protected $oAuthRestClient;
    /** @var string $salesforceOauthUrl */
    protected $salesforceOauthUrl;
    /*Apex rest end points. Excludes the domain/host ($salesforceOauthUrl)*/
    protected $apexEndPoint;

    /**
     * ExtendedRestforce constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $salesforceOauthUrl
     * @param \EventFarm\Restforce\Rest\OAuthAccessToken|null $accessToken
     * @param string|null $username
     * @param string|null $password
     * @param string|null $apiVersion
     * @param string $apexEndPoint
     * @throws \EventFarm\Restforce\RestforceException
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $salesforceOauthUrl,
        OAuthAccessToken $accessToken = null,
        string $username = null,
        string $password = null,
        string $apiVersion = null,
        string $apexEndPoint = "/services/apexrest/"
    )
    {
        if ($accessToken === null && $username === null && $password === null) {
            throw RestforceException::minimumRequiredFieldsNotMet();
        }

        if ($apiVersion === null) {
            $apiVersion = self::DEFAULT_API_VERSION;
        }

        $this->apiVersion = $apiVersion;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = $accessToken;
        $this->username = $username;
        $this->password = $password;
        $this->salesforceOauthUrl = $salesforceOauthUrl;
        $this->apexEndPoint = $apexEndPoint;
    }

    /**
     * @param string $sobjectType
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(string $sobjectType, array $data): ResponseInterface
    {
        $uri = 'sobjects/' . $sobjectType;

        return $this->getOAuthRestClient()->postJson($uri, $data);
    }

    /**
     * @param string $sobjectType
     * @param string $sobjectId
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(string $sobjectType, string $sobjectId, array $data): ResponseInterface
    {
        $uri = 'sobjects/' . $sobjectType . '/' . $sobjectId;

        return $this->getOAuthRestClient()->patchJson($uri, $data);
    }

    /**
     * @param string $sobject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function describe(string $sobject): ResponseInterface
    {
        $uri = 'sobjects/' . $sobject . '/describe';

        return $this->getOAuthRestClient()->get($uri);
    }

    /**
     * @param string $sobjectType
     * @param string $sobjectId
     * @param array $fields
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find(string $sobjectType, string $sobjectId, array $fields = []): ResponseInterface
    {
        $uri = 'sobjects/' . $sobjectType . '/' . $sobjectId;

        $queryParams = [];

        if (!empty($fields)) {
            $fieldsString = implode(',', $fields);
            $queryParams = ['fields' => $fieldsString];
        }

        return $this->getOAuthRestClient()->get($uri, $queryParams);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function limits(): ResponseInterface
    {
        return $this->getOAuthRestClient()->get('/limits');
    }

    /**
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getNext(string $url): ResponseInterface
    {
        return $this->getOAuthRestClient()->get($url);
    }

    /**
     * @param string $queryString
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function query(string $queryString): ResponseInterface
    {
        return $this->getOAuthRestClient()->get('query', [
            'q' => $queryString,
        ]);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function userInfo(): ResponseInterface
    {
        return $this->getOAuthRestClient()->get(self::USER_INFO_ENDPOINT);
    }

    /**
     * @return \Salesforce\Restforce\ExtendedRestClientInterface
     */
    private function getOAuthRestClient(): RestClientInterface
    {
        if ($this->oAuthRestClient === null) {
            $this->oAuthRestClient = new ExtendedOAuthRestClient(
                new ExtendedSalesforceRestClient(
                    new ExtendedGuzzleRestClient('https://na1.salesforce.com'),
                    $this->apiVersion
                ),
                new ExtendedGuzzleRestClient($this->salesforceOauthUrl),
                $this->clientId,
                $this->clientSecret,
                $this->username,
                $this->password,
                $this->accessToken
            );
        }

        return $this->oAuthRestClient;
    }

    /**
     * @param string $sobjectType object name
     * @param string $sobjectId object id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function findApexObject(string $sobjectType, string $sobjectId): ResponseInterface
    {
        $uri = $this->apexEndPoint . $sobjectType . '/' . $sobjectId;

        return $this->getOAuthRestClient()->get($this->salesforceOauthUrl . $uri);
    }

    /**
     * @param string $sobjectType object name
     * @param array $data data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createApexObject(string $sobjectType, array $data): ResponseInterface
    {
        $uri = $this->apexEndPoint . $sobjectType . '/';

        return $this->getOAuthRestClient()->post($this->salesforceOauthUrl . $uri, $data);
    }

    /**
     * @param string $sobjectType object name
     * @param array $data data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateApexObject(string $sobjectType, array $data): ResponseInterface
    {
        $uri = $this->apexEndPoint . $sobjectType . '/';

        return $this->getOAuthRestClient()->patchJson($this->salesforceOauthUrl . $uri, $data);
    }

    /**
     * @param string|null $uri
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function apexGet(string $uri = null): ResponseInterface
    {
        return $this->getOAuthRestClient()->get($this->salesforceOauthUrl . $this->apexEndPoint . $uri);
    }

    /**
     * @param string|null $uri
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function apexPostJson(string $uri = null, array $data = null): ResponseInterface
    {
        return $this->getOAuthRestClient()->postJson($this->salesforceOauthUrl . $this->apexEndPoint . $uri, $data);
    }

    /**
     * @param string|null $uri
     * @param array|null $data
     * @return ResponseInterface
     */
    public function createJob(string $uri = null, array $data = null): ResponseInterface
    {
        return $this->getOAuthRestClient()->postJson(self::DEFAULT_JOB_BASE_URI . $uri, $data);
    }

    /**
     * @param string|null $uri
     * @param string|null $csvdata
     * @return ResponseInterface
     */
    public function addToJobBatches(string $uri = null, string $csvdata = null): ResponseInterface
    {
        return $this->getOAuthRestClient()->putCsv(self::DEFAULT_JOB_BASE_URI . $uri, $csvdata);
    }

    /**
     * @param string|null $uri
     * @param array|null $data
     * @return ResponseInterface
     */
    public function closeJob(string $uri = null, array $data = null): ResponseInterface
    {
        return $this->getOAuthRestClient()->patchJson(self::DEFAULT_JOB_BASE_URI . $uri, $data);
    }

    /**
     * @param $uri
     * @return ResponseInterface
     */
    public function jobGet($uri): ResponseInterface
    {
        return $this->getOAuthRestClient()->get(self::DEFAULT_JOB_BASE_URI . $uri);
    }

}
