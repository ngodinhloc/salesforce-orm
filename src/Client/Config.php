<?php
namespace Salesforce\Client;

use Salesforce\Client\Exception\ConfigException;

class Config
{
    protected $clientId;
    protected $clientSecret;
    protected $path;
    protected $username;
    protected $password;
    protected $apiVersion;

    /**
     * Config constructor.
     *
     * @throws ConfigException
     */
    public function __construct()
    {
        try {
            $this->clientId = getenv('Salesforce.clientId');
            $this->clientSecret = getenv('Salesforce.clientSecret');
            $this->path = getenv('Salesforce.path');
            $this->username = getenv('Salesforce.username');
            $this->password = getenv('Salesforce.password');
            $this->apiVersion = getenv('Salesforce.apiVersion');
        } catch (\Exception $exception) {
            throw new ConfigException(ConfigException::MSG_FAILED_READING_EVN . $exception->getMessage());
        }

        if (!$this->clientId || !$this->clientSecret || !$this->path || !$this->username || !$this->password || !$this->apiVersion) {
            throw new ConfigException(ConfigException::MSG_MISSING_SALESFORCE_ENV);
        }
    }

    /**
     * @return array|false|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param array|false|string $clientId client id
     * @return Config
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param array|false|string $clientSecret secret
     * @return Config
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array|false|string $path path
     * @return Config
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param array|false|string $username username
     * @return Config
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array|false|string $password password
     * @return Config
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param array|false|string $apiVersion api version
     * @return Config
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }
}
