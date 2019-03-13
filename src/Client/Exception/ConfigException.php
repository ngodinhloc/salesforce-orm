<?php
namespace Salesforce\Client\Exception;

class ConfigException extends \Exception
{
    const MSG_MISSING_SALESFORCE_CONFIG = "Salesforce configs are missing. Required following data: ";
}
