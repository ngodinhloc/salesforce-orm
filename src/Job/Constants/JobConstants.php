<?php
namespace Salesforce\Job\Constants;

use Salesforce\Constants\Constants;

class JobConstants extends Constants
{
    CONST TYPE_BULK = 'Bulk';
    CONST TYPE_BULK_QUERY = 'BulkQuery';

    CONST OPERATION_INSERT = 'insert';
    CONST OPERATION_UPDATE = 'update';
    CONST OPERATION_UPSERT = 'upsert';
    CONST OPERATION_DELETE = 'delete';

    CONST JOB_FIELD_ID = 'id';
    CONST JOB_FIELD_STATE = 'state';
    CONST JOB_FIELD_EXTERNAL_ID_FIELD_NAME = 'externalIdFieldName';

    CONST STATE_OPEN = 'Open';
    CONST STATE_UPLOAD_COMPLETE = 'UploadComplete';
    CONST STATE_JOB_PROCESS_COMPLETE = 'JobComplete';

    CONST JOB_RESULT_SUCCESSFUL = 'successfulResults';
    CONST JOB_RESULT_FAILED = 'failedResults';
    CONST JOB_RESULT_UNPROCESSED = 'unprocessedRecords';

    CONST JOB_INGEST_ENDPOINT = 'ingest/';
    CONST JOB_ADD_BATCHES_ENDPOINT = 'batches/';

    CONST JOB_RESULT_PASSED_RESULT_ENDPOINT = self::JOB_RESULT_SUCCESSFUL . '/';
    CONST JOB_RESULT_FAILED_RESULT_ENDPOINT = self::JOB_RESULT_FAILED . '/';
    CONST JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT = self::JOB_RESULT_UNPROCESSED . '/';

    CONST GET_RESULT_RETRY = 10;
}
