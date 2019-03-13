<?php
namespace SalesforceTest\ORM;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Salesforce\Client\ResponseCodes;
use Salesforce\ORM\Query\Result;

class ResultTest extends TestCase
{
    /** @var Result */
    protected $result;

    public function setUp()
    {
        parent::setUp();
        $this->result = new Result();
    }

    public function testGet()
    {
        /*
         * Return false
         */
        $statusCode = ResponseCodes::HTTP_NON_AUTHORITY;
        $response = new Response($statusCode);
        $this->result->setResponse($response);
        $this->assertEquals(false, $this->result->get());

        /*
         * Return true
         */
        $statusCode = ResponseCodes::HTTP_NO_CONTENT;
        $response = new Response($statusCode);
        $this->result->setResponse($response);
        $this->assertEquals(true, $this->result->get());

        /*
         * Return array
         */
        $statusCode = ResponseCodes::HTTP_OK;
        $body = json_encode(["records" => []]);
        $response = new Response($statusCode, [], $body);
        $this->result->setResponse($response);
        $this->assertTrue(is_array($this->result->get()));
    }
}
