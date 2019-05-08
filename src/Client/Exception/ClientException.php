<?php
namespace Salesforce\Client\Exception;

use Salesforce\Exception\SalesforceException;

class ClientException extends SalesforceException
{
    const MSG_APEX_API_URI_MISSING = 'Uri string empty.';
    const MSG_APEX_API_FAILED = 'Apex api failed: ';
    const MSG_QUERY_MISSING = 'Query string empty.';
    const MSG_OBJECT_TYPE_MISSING = 'Object Type is missing.';
    const MSG_OBJECT_ID_MISSING = 'Object Id is missing.';
    const MSG_METHOD_NOT_EXISTS = 'Method does not exist: ';
    const MSG_FAILED_TO_CREATE_OBJECT = 'Failed to creat object: ';
    const MSG_FAILED_TO_UPDATE_OBJECT = 'Failed to update object: ';
    const MSG_FAILED_TO_FIND_OBJECT = 'Failed to find object: ';
}
