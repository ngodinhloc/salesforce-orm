<?php
namespace Salesforce\ORM\Exception;

use Salesforce\Exception\SalesforceException;

class BulkException extends SalesforceException
{
    const MSG_JOB_CREATION_FAILED = 'Job creation falied.';
    const MSG_JOB_BATCH_UPLOAD_FAILED = 'Job batch upload falied.';
    const MSG_JOB_GET_RESULT_FAILED = 'Job failed to get result.';
}