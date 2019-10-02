<?php
namespace Salesforce\Job\Exception;

use Salesforce\Exception\SalesforceException;

class JobException extends SalesforceException
{
    const MSG_CREATION_FAILED = 'Job creation failed.';
    const MSG_BATCH_UPLOAD_FAILED = 'Job batch upload failed.';
    const MSG_CLOSE_FAILED = 'Job batch upload failed.';
    const MSG_GET_RESULT_FAILED = 'Job failed to get result.';
    const MSG_MISSING_EXTERNAL_ID = 'External Id field name is missing for upsert.';
}