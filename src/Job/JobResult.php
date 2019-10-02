<?php
namespace Salesforce\Job;

use League\Csv\Reader;
use Salesforce\Client\Connection;
use Salesforce\Job\Constants\JobConstants;

class JobResult
{
    /** @var Job */
    protected $job;

    /** @var Connection */
    protected $connection;

    public function __construct(Job $job, Connection $connection)
    {
        $this->job = $job;
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getSuccessFullResult()
    {
        $passedResult =$this->connection->getClient()->bulkJobGet(JobConstants::JOB_INGEST_ENDPOINT . $this->job->getId() . '/' . JobConstants::JOB_RESULT_PASSED_RESULT_ENDPOINT);

        return Reader::createFromString($passedResult)->jsonSerialize();
    }

    /**
     * @return array
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getFailedFullResult()
    {
        $failedResult = $this->connection->getClient()->bulkJobGet(JobConstants::JOB_INGEST_ENDPOINT . $this->job->getId() . '/' . JobConstants::JOB_RESULT_FAILED_RESULT_ENDPOINT);

        return Reader::createFromString($failedResult)->jsonSerialize();
    }

    /**
     * @return array
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getUnprocessedRecord()
    {
        $unprocessedRecords = $this->connection->getClient()->bulkJobGet(JobConstants::JOB_INGEST_ENDPOINT . $this->job->getId() . '/' . JobConstants::JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT);

        return Reader::createFromString($unprocessedRecords)->jsonSerialize();
    }

    /**
     * @return array
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getAll()
    {
        $result = [];

        $result[JobConstants::JOB_RESULT_SUCCESSFUL] = $this->getSuccessFullResult();
        $result[JobConstants::JOB_RESULT_FAILED] = $this->getFailedFullResult();
        $result[JobConstants::JOB_RESULT_UNPROCESSED] = $this->getUnprocessedRecord();

        return $result;
    }
}