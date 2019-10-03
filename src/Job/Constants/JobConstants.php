<?php
namespace Salesforce\Job\Constants;

use Salesforce\Constants\Constants;

class JobConstants extends Constants
{
    const TYPE_BULK = 'Bulk';
    const TYPE_BULK_QUERY = 'BulkQuery';

    const OPERATION_INSERT = 'insert';
    const OPERATION_UPDATE = 'update';
    const OPERATION_UPSERT = 'upsert';
    const OPERATION_DELETE = 'delete';

    const JOB_FIELD_ID = 'id';
    const JOB_FIELD_STATE = 'state';
    const JOB_FIELD_EXTERNAL_ID_FIELD_NAME = 'externalIdFieldName';

    const STATE_OPEN = 'Open';
    const STATE_UPLOAD_COMPLETE = 'UploadComplete';
    const STATE_JOB_PROCESS_COMPLETE = 'JobComplete';
    const STATE_ABORTED = 'Aborted';
    const STATE_FAILED = 'Failed';

    const JOB_RESULT_SUCCESSFUL = 'successfulResults';
    const JOB_RESULT_FAILED = 'failedResults';
    const JOB_RESULT_UNPROCESSED = 'unprocessedRecords';

    const JOB_INGEST_ENDPOINT = 'ingest/';
    const JOB_ADD_BATCHES_ENDPOINT = 'batches/';

    const JOB_RESULT_PASSED_RESULT_ENDPOINT = self::JOB_RESULT_SUCCESSFUL . '/';
    const JOB_RESULT_FAILED_RESULT_ENDPOINT = self::JOB_RESULT_FAILED . '/';
    const JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT = self::JOB_RESULT_UNPROCESSED . '/';

    const GET_RESULT_RETRY = 10;
}
