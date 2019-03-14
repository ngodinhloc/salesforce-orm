<?php
namespace Salesforce\Client\Exception;

class ResultException extends \Exception
{
    const MSG_NO_RESPONSE_PROVIDED = 'No response provided. Please set response.';
}
