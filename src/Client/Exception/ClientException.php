<?php
namespace Salesforce\Client\Exception;

class ClientException extends \Exception
{
    const MSG_METHOD_NOT_EXISTS = 'Method does not exist: ';
    const MSG_FAILED_TO_CREATE_OBJECT = 'Failed to creat object: ';
    const MSG_FAILED_TO_UPDATE_OBJECT = 'Failed to update object: ';
}
