<?php
namespace Salesforce\Restforce;

use EventFarm\Restforce\Rest\SalesforceRestClientInterface;
use Psr\Http\Message\ResponseInterface;

interface ExtendedSalesforceRestClientInterface extends SalesforceRestClientInterface
{
    public function putCsv(
        string $path,
        string $csvData = null,
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface;
}
