<?php
namespace Salesforce\Client\Exception;

use Salesforce\Exception\SalesforceException;

class ResultException extends SalesforceException
{
    const MSG_NO_RESPONSE_PROVIDED = 'No response provided. Please set response.';
}
