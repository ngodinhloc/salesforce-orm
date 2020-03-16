<?php
namespace Salesforce\Restforce;

use Psr\Http\Message\ResponseInterface;

class ExtendedGuzzleRestClient implements ExtendedRestClientInterface
{
    const DEFAULT_TIMEOUT_SECONDS = 20.0;

    /** @var \GuzzleHttp\Client */
    protected $client;
    /** @var bool */
    protected $enableDebugging;

    public function __construct(
        string $baseUri,
        bool $enableDebugging = false
    ) {
        $this->enableDebugging = $enableDebugging;
        $this->setBaseUriForRestClient($baseUri);
    }

    public function setBaseUriForRestClient(string $baseUri)
    {
        if (!$this->containsTrailingSlash($baseUri)) {
            $baseUri .= '/';
        }

        $config = [
            'base_uri' => $baseUri,
            'debug' => $this->enableDebugging
        ];

        $this->client = new \GuzzleHttp\Client($config);
    }

    public function get(
        string $path,
        array $queryParameters = [],
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface {
        return $this->client->request(
            'GET',
            $path,
            [
                'timeout' => $timeoutSeconds,
                'headers' => $headers,
                'query' => $queryParameters,
                'http_errors' => false,
            ]
        );
    }

    public function post(
        string $path,
        array $formParameters = [],
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface {
        return $this->client->request(
            'POST',
            $path,
            [
                'timeout' => $timeoutSeconds,
                'headers' => $headers,
                'form_params' => $formParameters,
                'http_errors' => false,
            ]
        );
    }

    public function postJson(
        string $path,
        array $jsonArray = [],
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface {
        $headers['Content-Type'] = 'application/json';

        return $this->client->request(
            'POST',
            $path,
            [
                'timeout' => $timeoutSeconds,
                'headers' => $headers,
                'json' => $jsonArray,
                'http_errors' => false,
            ]
        );
    }

    public function patchJson(
        string $path,
        array $jsonArray = [],
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface {
        $headers['Content-Type'] = 'application/json';

        return $this->client->request(
            'PATCH',
            $path,
            [
                'timeout' => $timeoutSeconds,
                'headers' => $headers,
                'json' => $jsonArray,
                'http_errors' => false,
            ]
        );
    }

    public function putCsv(
        string $path = null,
        string $csvData = null,
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface {
        $headers['Content-Type'] = 'text/csv';
        $headers['Accept'] = 'application/json';

        return $this->client->request(
            'PUT',
            $path,
            [
                'timeout' => $timeoutSeconds,
                'headers' => $headers,
                'body' => $csvData,
                'http_errors' => false,
            ]
        );
    }

    protected function containsTrailingSlash(string $baseUri): bool
    {
        return substr($baseUri, -1) === '/';
    }
}
