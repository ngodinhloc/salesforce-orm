<?php
namespace Salesforce\Job\Bulk;

use Salesforce\Job\BulkImportInterface;
use Salesforce\Job\Exception\JobException;
use Salesforce\Job\Job;
use Salesforce\Job\JobInterface;
use Salesforce\ORM\Exception\EntityException;

class DeleteJob extends Job implements JobInterface, BulkImportInterface
{
    /** @var array */
    protected $csvData = [];

    /** @var string */
    protected $operation = Job::OPERATION_DELETE;

    /**
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\Job\Exception\JobException
     */
    public function validate(): bool
    {
        $data = $this->getCsvData();

        if (empty($data) || count($data) < 2) {
            throw new  JobException(JobException::MSG_JOB_DATA_MISSING);
        }

        $header = array_shift($data);

        $rowSize = count($header);

        foreach($data as $row) {
            if (count($row) !== $rowSize) {
                throw new EntityException(EntityException::MGS_CSV_ROW_COUNT_MISMATCH);
            }

            if (count($row) > 1) {
                throw new JobException(JobException::MSG_OPERATION_DELETE_VALIDATION_FAILED);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getCsvData(): array
    {
        return $this->csvData;
    }

    /**
     * @param array $csvData
     */
    public function setCsvData(array $csvData)
    {
        $this->csvData = $csvData;
    }
}
