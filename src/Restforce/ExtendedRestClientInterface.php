<?php
namespace Salesforce\Restforce;

use EventFarm\Restforce\Rest\RestClientInterface;
use Psr\Http\Message\ResponseInterface;

interface ExtendedRestClientInterface extends RestClientInterface
{
    public function putCsv(
        string $path,
        string $csvData = null,
        array $headers = [],
        float $timeoutSeconds = null
    ): ResponseInterface;
}
