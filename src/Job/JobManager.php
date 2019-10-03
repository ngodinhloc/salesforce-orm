<?php
namespace Salesforce\Job;

use League\Csv\Reader;
use League\Csv\Writer;
use Salesforce\Client\Connection;
use Salesforce\Event\EventDispatcherInterface;
use Salesforce\Job\Constants\JobConstants;
use Salesforce\ORM\EntityFactory;
use Salesforce\Job\Exception\JobException;
use Salesforce\ORM\Exception\EntityException;
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
     * @param \Salesforce\ORM\EntityFactory|null $entityFactory
     * @param \Salesforce\ORM\Mapper|null $mapper mapper
     * @param \Salesforce\Event\EventDispatcherInterface|null $eventDispatcher
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(Connection $conn = null, EntityFactory $entityFactory = null, Mapper $mapper = null, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->connection = $conn;
        $this->entityFactory = $entityFactory ?: new EntityFactory();
        $this->mapper = $mapper ?: new Mapper();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $className
     * @param \Salesforce\Job\Job $job
     * @throws \Salesforce\Job\Exception\JobException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function registerJob(string $className, Job &$job)
    {
        $data = [];
        $entity = $this->mapper->object($className);
        $patchedEntity = $this->mapper->patch($entity, []);
        $object = $this->mapper->getObjectType($patchedEntity);

        $job->setObject($object);
        $job->setEntity($patchedEntity);

        $operation = $job->getOperation();

        if ($operation === JobConstants::OPERATION_UPSERT) {
            $externalId = $job->getExternalId();
            if (empty($externalId)) {
                throw new JobException(JobException::MSG_MISSING_EXTERNAL_ID);
            }
            $data[JobConstants::JOB_FIELD_EXTERNAL_ID_FIELD_NAME] = $externalId;
        }

        $jobResponse = $this->connection->getClient()->createJob($object, $operation, $data);

        $job->setId($jobResponse[JobConstants::JOB_FIELD_ID]);
        $job->setState($jobResponse[JobConstants::JOB_FIELD_STATE]);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @param array $header
     * @param array $data
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Job\Exception\JobException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \TypeError
     */
    public function processJob(Job &$job, array $header, array $data)
    {
        switch ($job->getType()) {
            case JobConstants::TYPE_BULK:
                $this->batchJob($job, $header, $data);
                break;
        }
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @param array $header
     * @param array $data
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Job\Exception\JobException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \TypeError
     */
    protected function batchJob(Job &$job, array $header, array $data)
    {
        $this->validateJobBatchData($job, $header, $data);

        //load the CSV document from a string
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($data);

        $jobAddedSuccessfully = $this->connection->getClient()->addToJobBatches($job->getId(), $csv->getContent());

        if ($jobAddedSuccessfully !== true) {
            throw new JobException(JobException::MSG_BATCH_UPLOAD_FAILED);
        }

        $job->setState(JobConstants::STATE_UPLOAD_COMPLETE);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @throws JobException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function closeJob(Job &$job)
    {
        $closedJob = $this->connection->getClient()->closeJob($job->getId());

        if ($closedJob[JobConstants::JOB_FIELD_STATE] !== JobConstants::STATE_UPLOAD_COMPLETE) {
            throw new JobException(JobException::MSG_CLOSE_FAILED);
        }

        $job->setState($closedJob[JobConstants::JOB_FIELD_STATE]);
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function getJobInfo(Job &$job)
    {
        $jobInfo = $this->connection->getClient()->jobGet(JobConstants::JOB_INGEST_ENDPOINT . $job->getId());
        $job->setState($jobInfo[JobConstants::JOB_FIELD_STATE]);
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

        $passedResult =$this->connection->getClient()->jobGet(JobConstants::JOB_INGEST_ENDPOINT . $job->getId() . '/' . JobConstants::JOB_RESULT_PASSED_RESULT_ENDPOINT);

        $jobResult->setSuccessfulResult(Reader::createFromString($passedResult)->jsonSerialize());

        $failedResult = $this->connection->getClient()->jobGet(JobConstants::JOB_INGEST_ENDPOINT . $job->getId() . '/' . JobConstants::JOB_RESULT_FAILED_RESULT_ENDPOINT);

        $jobResult->setFailedResult(Reader::createFromString($failedResult)->jsonSerialize());

        $unprocessedRecords = $this->connection->getClient()->jobGet(JobConstants::JOB_INGEST_ENDPOINT . $job->getId() . '/' . JobConstants::JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT);

        $jobResult->setUnprocessedRecords(Reader::createFromString($unprocessedRecords)->jsonSerialize());

        return $jobResult;
    }

    /**
     * @param \Salesforce\Job\Job $job
     * @param array $header
     * @param array $data
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Job\Exception\JobException
     */
    protected function validateJobBatchData(Job $job, array $header = [], array $data = [])
    {
        if (empty(get_class($job->getEntity()))) {
            throw new EntityException(EntityException::MGS_EMPTY_CLASS_NAME);
        }
        if (empty($header)) {
            throw new EntityException(EntityException::MGS_EMPTY_HEADER);
        }
        if (empty($data)) {
            throw new EntityException(EntityException::MGS_EMPTY_DATA);
        }

        $rowSize = count($header);

        foreach($data as $row) {
            if (count($row) !== $rowSize) {
                throw new EntityException(EntityException::MGS_CSV_ROW_COUNT_MISMATCH);
            }
            $row = array_combine($header, $row);

            $entity = $this->entityFactory->new(get_class($job->getEntity()), $row);

            if ($entity->isPatched() !== true) {
                $entity = $this->mapper->patch($entity, []);
            }

            switch ($job->getOperation()) {
                case JobConstants::OPERATION_INSERT:
                case JobConstants::OPERATION_UPSERT:
                    $checkRequiredProperties = $this->mapper->checkRequiredProperties($entity);
                    if ($checkRequiredProperties !== true) {
                        throw new EntityException(EntityException::MGS_REQUIRED_PROPERTIES . implode(", ", $checkRequiredProperties));
                    }

                    $checkRequiredValidations = $this->mapper->checkRequiredValidations($entity);
                    if ($checkRequiredValidations !== true) {
                        throw new EntityException(EntityException::MGS_REQUIRED_VALIDATIONS . implode(", ", $checkRequiredValidations));
                    }

                    $data = $this->mapper->getNoneProtectionData($entity);
                    if (!$this->mapper->checkNoneProtectionData($data)) {
                        throw new EntityException(EntityException::MGS_EMPTY_NONE_PROTECTION_DATA);
                    }
                    break;
                case JobConstants::OPERATION_UPDATE:
                    if (!$entity->getId()) {
                        throw new EntityException(EntityException::MGS_ID_IS_NOT_PROVIDED);
                    }

                    $checkRequiredValidations = $this->mapper->checkRequiredValidations($entity);
                    if ($checkRequiredValidations !== true) {
                        throw new EntityException(EntityException::MGS_REQUIRED_VALIDATIONS . implode(", ", $checkRequiredValidations));
                    }

                    $data = $this->mapper->getNoneProtectionData($entity);
                    if (!$this->mapper->checkNoneProtectionData($data)) {
                        throw new EntityException(EntityException::MGS_EMPTY_NONE_PROTECTION_DATA);
                    }
                    break;
                case JobConstants::OPERATION_DELETE:
                    if (!$entity->getId()) {
                        throw new EntityException(EntityException::MGS_ID_IS_NOT_PROVIDED);
                    }

                    if (count($row) > 1) {
                        throw new JobException(JobException::MSG_OPERATION_DELETE_VALIDATION_FAILED);
                    }
                    break;
            }
        }

        return true;
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
