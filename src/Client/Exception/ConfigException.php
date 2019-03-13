<?php
namespace Salesforce\Client\Exception;

class ConfigException extends \Exception
{
    const MSG_MISSING_SALESFORCE_ENV = "Salesforce env are missing. Please check your env configuration.";
    const MSG_FAILED_READING_EVN = "Failed to read env: ";
}
