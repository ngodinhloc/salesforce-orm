<?php
namespace Salesforce\Client;

use Psr\Http\Message\ResponseInterface;
use Salesforce\Client\Exception\ResultException;

class Result
{
    /* @var ResponseInterface|null */
    protected $response;

    /**
     * Result constructor.
     *
     * @param ResponseInterface|null $response response
     */
    public function __construct(ResponseInterface $response = null)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function get()
    {
        if (!$this->response) {
            throw new ResultException(ResultException::MSG_NO_RESPONSE_PROVIDED);
        }

        $result = false;
        switch ($this->response->getStatusCode()) {
            case ResponseCodes::HTTP_OK:
                if ($content = $this->response->getBody()->getContents()) {
                    $array = json_decode($content, true);
                    $result = isset($array['records']) ? $array['records'] : $array;
                }
                break;
            case ResponseCodes::HTTP_CREATED:
                if ($content = $this->response->getBody()->getContents()) {
                    $array = json_decode($content, true);
                    if ($array['success'] && isset($array['id'])) {
                        $result = $array['id'];
                    }
                }
                break;
            case ResponseCodes::HTTP_NOT_FOUND:
            case ResponseCodes::HTTP_BAD_REQUEST:
                if ($content = $this->response->getBody()->getContents()) {
                    $array = json_decode($content, true);
                    if (isset($array['message'])) {
                        throw new ResultException($array['message']);
                    }
                }
                break;
            case ResponseCodes::HTTP_NO_CONTENT:
                $result = true;
                break;

        }

        return $result;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface|null $response response
     * @return Result
     */
    public function setResponse(ResponseInterface $response): Result
    {
        $this->response = $response;

        return $this;
    }
}
