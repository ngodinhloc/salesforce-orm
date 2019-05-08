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
    protected $apexEndPoint;

    const REQUIRED_CONFIGURATION_DATA = ['clientId', 'clientSecret', 'path', 'username', 'password', 'apiVersion', 'apexEndPoint'];

    /**
     * Config constructor.
     *
     * @param array $config config
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     *  'apexEndPoint' =>
     * ]
     * @throws \Salesforce\Client\Exception\ConfigException
     */
    public function __construct(array $config = null)
    {
        if (!isset($config['clientId']) || !isset($config['clientSecret']) || !isset($config['path']) || !isset($config['username']) || !isset($config['password']) || !isset($config['apiVersion'])) {
            throw new ConfigException(ConfigException::MSG_MISSING_SALESFORCE_CONFIG . implode(",", self::REQUIRED_CONFIGURATION_DATA));
        }
        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->path = $config['path'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->apiVersion = $config['apiVersion'];
        $this->apexEndPoint = $config['apexEndPoint'];
    }

    /**
     * @return array|false|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId client id
     * @return \Salesforce\Client\Config
     */
    public function setClientId(string $clientId = null)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret secret
     * @return \Salesforce\Client\Config
     */
    public function setClientSecret(string $clientSecret = null)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path path
     * @return \Salesforce\Client\Config
     */
    public function setPath(string $path = null)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username username
     * @return \Salesforce\Client\Config
     */
    public function setUsername(string $username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password password
     * @return \Salesforce\Client\Config
     */
    public function setPassword(string $password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param string $apiVersion api version
     * @return \Salesforce\Client\Config
     */
    public function setApiVersion(string $apiVersion = null)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApexEndPoint()
    {
        return $this->apexEndPoint;
    }

    /**
     * @param mixed $apexEndPoint
     * @return \Salesforce\Client\Config
     */
    public function setApexEndPoint(string $apexEndPoint = null)
    {
        $this->apexEndPoint = $apexEndPoint;

        return $this;
    }
}
