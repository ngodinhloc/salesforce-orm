<?php
namespace Salesforce\Restforce;

use EventFarm\Restforce\RestforceInterface;

interface ExtendedRestforceInterface extends RestforceInterface
{
    public function apexApi(string $uri = null, array $data);
}
