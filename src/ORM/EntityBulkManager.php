<?php
namespace Salesforce\ORM;

use League\Csv\Reader;
use League\Csv\Writer;
use Salesforce\Client\Connection;
use Salesforce\Event\EventDispatcherInterface;
use Salesforce\ORM\Constants\BulkApiConstants;
use Salesforce\ORM\Exception\BulkApiException;
use Salesforce\ORM\Exception\BulkException;
use Salesforce\ORM\Exception\EntityException;
use Salesforce\ORM\Query\Builder;

/**
 * Class EntityBulkManager
 *
 * @package App\Domain\Marketing\Salesforce
 */
class EntityBulkManager
{
    /** @var Connection */
    protected $connection;

    /** @var Mapper */
    protected $mapper;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var EntityFactory */
    protected $entityFactory;

    /**
     * EntityBulkManager constructor.
     *
     * @param \Salesforce\Client\Connection|null $conn
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
     * @param array $header
     * @param array $data
     * @return array
     * @throws BulkException
     * @throws EntityException
     * @throws Exception\MapperException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \TypeError
     */
    public function bulkCreate(string $className, array $header, array $data)
    {
        $this->validateBulkData($className, $header, $data);
        $entity = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($entity);
        $jobDetails = $this->connection->getClient()->createBulkJob($objectType, BulkApiConstants::CREATE_JOB_ACTION_INSERT);

        return $this->processBulkData($jobDetails[BulkApiConstants::CREATE_JOB_ID], $header, $data);
    }

    /**
     * @param string $className
     * @param array $header
     * @param array $data
     * @return array
     * @throws BulkException
     * @throws EntityException
     * @throws Exception\MapperException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \TypeError
     */
    public function bulkUpdate(string $className, array $header, array $data)
    {
        $this->validateBulkData($className, $header, $data, true);
        $entity = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($entity);
        $jobDetails = $this->connection->getClient()->createBulkJob($objectType, BulkApiConstants::CREATE_JOB_ACTION_UPDATE);

        return $this->processBulkData($jobDetails[BulkApiConstants::CREATE_JOB_ID], $header, $data);
    }

    /**
     * @param string $className
     * @param array $header
     * @param array $data
     * @return array
     * @throws \TypeError
     * @throws BulkException
     * @throws EntityException
     * @throws Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \League\Csv\CannotInsertRecord
     */
    public function bulkUpsert(string $className, array $header, array $data, string $externalIdField = null)
    {
        $this->validateBulkData($className, $header, $data);
        $entity = $this->mapper->object($className);
        if ($externalIdField == null && $entity->isPatched() !== true) {
            $entity = $this->mapper->patch($entity, []);
            $externalIdField = key($entity->getExternalIds());
        }
        $objectType = $this->mapper->getObjectType($entity);
        $jobDetails = $this->connection->getClient()->createBulkJob($objectType, BulkApiConstants::CREATE_JOB_ACTION_UPSERT);

        return $this->processBulkData($jobDetails[BulkApiConstants::CREATE_JOB_ID], $header, $data);
    }

    /**
     * @param string $jobId
     * @param array $header
     * @param array $data
     * @return array
     * @throws BulkException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \TypeError
     */
    protected function processBulkData(string $jobId, array $header, array $data)
    {
        //load the CSV document from a string
        $csv = Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($data);

        $jobAddedSuccessfully = $this->connection->getClient()->addToBulkJobBatches($jobId, $csv->getContent());

        // Add data to batches
        if ($jobAddedSuccessfully !== true) {
            throw new BulkException(BulkException::MSG_JOB_CREATION_FAILED);
        }

        $closedJob = $this->connection->getClient()->closeBulkJob($jobId);

        if ($closedJob[BulkApiConstants::JOB_FIELD_LABEL_STATE] !== BulkApiConstants::JOB_FIELD_VALUE_JOB_UPLOAD_COMPLETE) {
            throw new BulkException(BulkException::MSG_JOB_CREATION_FAILED);
        }

        return $this->getJobResult($jobId);
    }

    /**
     * @param string|null $className
     * @param array $header
     * @param array $data
     * @param bool $update
     * @return bool
     * @throws EntityException
     * @throws Exception\MapperException
     */
    protected function validateBulkData(string $className = null, array $header = [],  array $data = [], $update = false)
    {
        if (empty($className)) {
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
            
            $entity = $this->entityFactory->new($className, $row);

            if ($entity->isPatched() !== true) {
                $entity = $this->mapper->patch($entity, []);
            }

            if ($update === true && !$entity->getId()) {
                throw new EntityException(EntityException::MGS_ID_IS_NOT_PROVIDED);
            }

            $checkRequiredProperties = $this->mapper->checkRequiredProperties($entity);
            if ($update === false && $checkRequiredProperties !== true) {
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
        }

        return true;
    }

    /**
     * @param string $jobId
     * @param int $retry
     * @return array
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws BulkException
     */
    public function getJobResult(string $jobId, $retry = 0)
    {
        $result = [];
        $jobInfo = $this->connection->getClient()->bulkJobGet(BulkApiConstants::JOB_INGEST_ENDPOINT . $jobId);
        if ($retry > BulkApiConstants::GET_RESULT_RETRY) {
            throw new BulkException(BulkException::MSG_JOB_GET_RESULT_FAILED);
        }

        if ($jobInfo[BulkApiConstants::JOB_FIELD_LABEL_STATE] != BulkApiConstants::JOB_FIELD_VALUE_JOB_PROCESS_COMPLETE) {
            sleep(rand(3,10));
            return $this->getJobResult($jobId, $retry++);
        }

        $passedResult =$this->connection->getClient()->bulkJobGet(BulkApiConstants::JOB_INGEST_ENDPOINT . $jobId . '/' . BulkApiConstants::JOB_RESULT_PASSED_RESULT_ENDPOINT);
        $result[BulkApiConstants::JOB_RESULT_SUCCESSFUL] = Reader::createFromString($passedResult)->jsonSerialize();

        $failedResult = $this->connection->getClient()->bulkJobGet(BulkApiConstants::JOB_INGEST_ENDPOINT . $jobId . '/' . BulkApiConstants::JOB_RESULT_FAIELD_RESULT_ENDPOINT);
        $result[BulkApiConstants::JOB_RESULT_FAILED] = Reader::createFromString($failedResult)->jsonSerialize();

        $unprocessedRecords = $this->connection->getClient()->bulkJobGet(BulkApiConstants::JOB_INGEST_ENDPOINT . $jobId . '/' . BulkApiConstants::JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT);
        $result[BulkApiConstants::JOB_RESULT_UNPROCESSED] = Reader::createFromString($unprocessedRecords)->jsonSerialize();

        return $result;
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
     * @param EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
