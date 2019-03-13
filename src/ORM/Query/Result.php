<?php
namespace Salesforce\ORM\Query;

use Psr\Http\Message\ResponseInterface;
use Salesforce\Client\ResponseCodes;
use Salesforce\ORM\Exception\ResultException;

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
     * @return bool|array
     * @throws \Salesforce\ORM\Exception\ResultException
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
                    $result = json_decode($content, true)['records'];
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
