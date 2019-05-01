<?php
namespace Salesforce\Client\Exception;

use Salesforce\Exception\SalesforceException;

class ConfigException extends SalesforceException
{
    const MSG_MISSING_SALESFORCE_CONFIG = "Salesforce configs are missing. Required following data: ";
}
