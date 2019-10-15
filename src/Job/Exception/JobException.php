<?php
namespace Salesforce\Job\Exception;

use Salesforce\Exception\SalesforceException;

class JobException extends SalesforceException
{
    const MSG_CREATION_FAILED = 'Job creation failed.';
    const MSG_BATCH_UPLOAD_FAILED = 'Job batch upload failed.';
    const MSG_FAILED_QUERY_REQUIRED_FOR_QUERY_JOB = 'Query is required for query Job.';
    const MSG_CLOSE_FAILED = 'Job batch upload failed.';
    const MSG_GET_RESULT_FAILED = 'Job failed to get result.';
    const MSG_MISSING_EXTERNAL_ID = 'External Id field name is missing for upsert.';
    const MSG_OPERATION_DELETE_VALIDATION_FAILED = 'Delete operation can only have ID passed for the object.';
    const MSG_JOB_DATA_MISSING = 'Job data is missing';

    const MSG_EXTERNAL_ID_FIELD_IS_REQUIRED = 'External ID Field is required for Upsert Job';
    const MSG_UPSERT_DATA_CANNOT_HAVE_ID_ASSIGNED = 'Upsert Job cannot have ID assigned to it since it uses External Id.';
}
