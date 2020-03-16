<?php
namespace Salesforce\Client;

use Psr\Http\Message\ResponseInterface;
use Salesforce\Client\Exception\ResultException;
use Salesforce\Job\Job;

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
                    if (current($this->response->getHeader('Content-Type')) == 'text/csv') {
                        return $content;
                    }
                    $array = json_decode($content, true);
                    if (isset($array['error']) && isset($array['message'])) {
                        throw new ResultException($array['message']);
                    }
                    if (isset($array['state']) && in_array($array['state'], [Job::STATE_FAILED, Job::STATE_ABORTED])) {
                        throw new ResultException($array['errorMessage']);
                    }
                    $result = isset($array['records']) ? $array['records'] : $array;
                }
                break;
            case ResponseCodes::HTTP_CREATED:
                if ($content = $this->response->getBody()->getContents()) {
                    $array = json_decode($content, true);
                    if ($array['success'] && isset($array['id'])) {
                        $result = $array['id'];
                        break;
                    }
                }
                $result = true;
                break;
            case ResponseCodes::HTTP_NOT_FOUND:
            case ResponseCodes::HTTP_BAD_REQUEST:
            case ResponseCodes::HTTP_UNSUPPORTED_MEDIA_TYPE:
            case ResponseCodes::HTTP_SERVER_ERROR:
                if ($content = $this->response->getBody()->getContents()) {
                    $array = json_decode($content, true);
                    if (isset($array[0]['message'])) {
                        throw new ResultException($array[0]['message']);
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
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface|null $response response
     * @return \Salesforce\Client\Result
     */
    public function setResponse(ResponseInterface $response = null)
    {
        $this->response = $response;

        return $this;
    }
}
