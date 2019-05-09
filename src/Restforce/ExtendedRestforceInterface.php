<?php
namespace Salesforce\Restforce;

use EventFarm\Restforce\RestforceInterface;
use Psr\Http\Message\ResponseInterface;

interface ExtendedRestforceInterface extends RestforceInterface
{
    public function apexGet(string $uri = null): ResponseInterface;

    public function apexPostJson(string $uri = null, array $data = null): ResponseInterface;
}
