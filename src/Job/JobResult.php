<?php
namespace Salesforce\Job;

use Salesforce\Job\Constants\JobConstants;

class JobResult
{
    /** @var array */
    protected $successfulResult;

    /** @var array */
    protected $unprocessedRecords;

    /** @var array */
    protected $failedResult;

    /**
     * @return array
     */
    public function getSuccessfulResult(): array
    {
        return $this->successfulResult;
    }

    /**
     * @param array $successfulResult
     */
    public function setSuccessfulResult(array $successfulResult)
    {
        $this->successfulResult = $successfulResult;
    }

    /**
     * @return array
     */
    public function getUnprocessedRecords(): array
    {
        return $this->unprocessedRecords;
    }

    /**
     * @param array $unprocessedRecords
     */
    public function setUnprocessedRecords(array $unprocessedRecords)
    {
        $this->unprocessedRecords = $unprocessedRecords;
    }

    /**
     * @return array
     */
    public function getFailedResult(): array
    {
        return $this->failedResult;
    }

    /**
     * @param array $failedResult
     */
    public function setFailedResult(array $failedResult)
    {
        $this->failedResult = $failedResult;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = [];

        $result[JobConstants::JOB_RESULT_SUCCESSFUL] = $this->getSuccessfulResult();
        $result[JobConstants::JOB_RESULT_FAILED] = $this->getFailedResult();
        $result[JobConstants::JOB_RESULT_UNPROCESSED] = $this->getUnprocessedRecords();

        return $result;
    }
}