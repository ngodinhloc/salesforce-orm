<?php
namespace Salesforce\Job;

use League\Csv\Reader;
use League\Csv\Writer;
use Salesforce\Client\Connection;
use Salesforce\Event\EventDispatcherInterface;
use Salesforce\Job\Exception\JobException;
use Salesforce\ORM\Mapper;

/**
 * Class JobManager
 *
 * @package App\Domain\Marketing\Salesforce
 */
class JobManager
{
    /** @var \Salesforce\Client\Connection */
    protected $connection;

    /** @var \Salesforce\ORM\Mapper */
    protected $mapper;

    /** @var \Salesforce\Event\EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var \Salesforce\ORM\EntityFactory */
    protected $entityFactory;

    /**
     * JobManager constructor.
     *
     * @param \Salesforce\Client\Connection|null $conn
     * @param \Salesforce\ORM\Mapper|null $mapper mapper
     * @param \Salesforce\Event\EventDispatcherInterface|null $eventDispatcher
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(Connection $conn = null, Mapper $mapper = null, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->connection = $conn;
        $this->mapper = $mapper ?: new Mapper();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $className
     * @param \Salesforce\Job\Job $job
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function registerJob(string $className, Job &$job)
    {
        $entity = $this->mapper->object($className);
        $patchedEntity = $this->mapper->patch($entity, []);
        $object = $this->mapper->getObjectType($patchedEntity);

        $job->setObject($object);
        $job->setEntity($patchedEntity);

        $operation = $job->getOperation();

        if ($job instanceof BulkImportInterface) {
            /** @var JobInterface $job */
            $job->validate();
        }
        $jobResponse = $this->connection->getClient()->createJob($job->getBaseUrl(), $object, $operation, $job->getRequestBody());
        $job->setId($jobResponse[Job::JOB_FIELD_ID]);
        $job->setState($jobResponse[Job::JOB_FIELD_STATE]);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @throws JobException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \TypeError
     */
    public function processBatchJob(Job &$job)
    {
        if (!$job instanceof BulkImportInterface) {
            return;
        }

        $data = $job->getCsvData();

        $header = array_shift($data);

        //load the CSV document from a string
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($data);

        $jobAddedSuccessfully = $this->connection->getClient()->batchJob($job->getBaseUrl() . $job->getId() . '/' . Job::JOB_ADD_BATCHES_ENDPOINT, $csv->getContent());

        if ($jobAddedSuccessfully !== true) {
            throw new JobException(JobException::MSG_BATCH_UPLOAD_FAILED);
        }

        $job->setState(Job::STATE_UPLOAD_COMPLETE);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @throws \Salesforce\Job\Exception\JobException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function closeJob(Job &$job)
    {
        if (!$job instanceof BulkImportInterface) {
            return;
        }

        $closedJob = $this->connection->getClient()->closeJob($job->getBaseUrl() . $job->getId());

        if ($closedJob[Job::JOB_FIELD_STATE] !== Job::STATE_UPLOAD_COMPLETE) {
            throw new JobException(JobException::MSG_CLOSE_FAILED);
        }

        $job->setState($closedJob[Job::JOB_FIELD_STATE]);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getJobInfo(Job &$job)
    {
        $jobInfo = $this->connection->getClient()->getJob($job->getBaseUrl() . $job->getId());
        $job->setState($jobInfo[Job::JOB_FIELD_STATE]);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @return \Salesforce\Job\JobResult
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getJobResult(Job $job)
    {
        $jobResult = new JobResult();
        $successfulResult =$this->connection->getClient()->getJob($job->getBaseUrl() . $job->getId() . '/' . $job->getSuccessResultUrl());

        $jobResult->setSuccessfulResult(Reader::createFromString($successfulResult)->jsonSerialize());

        if (!$job instanceof BulkImportInterface) {
            return $jobResult;
        }

        $failedResult = $this->connection->getClient()->getJob($job->getBaseUrl() . $job->getId() . '/' . $job->getFailedResultUrl());

        $jobResult->setFailedResult(Reader::createFromString($failedResult)->jsonSerialize());

        $unprocessedRecords = $this->connection->getClient()->getJob($job->getBaseUrl() . $job->getId() . '/' . $job->getUnprocessedResultUrl());

        $jobResult->setUnprocessedRecords(Reader::createFromString($unprocessedRecords)->jsonSerialize());

        return $jobResult;
    }

    /**
     * @return \Salesforce\Client\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param \Salesforce\Client\Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection = null)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return \Salesforce\ORM\Mapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param \Salesforce\ORM\Mapper $mapper mapper
     * @return $this
     */
    public function setMapper(Mapper $mapper = null)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * @return \Salesforce\Event\EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param \Salesforce\Event\EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
