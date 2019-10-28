<?php
namespace Salesforce\Job;
use Salesforce\ORM\Entity;
use Salesforce\ORM\EntityFactory;
use Salesforce\ORM\Mapper;

class Job
{
    const TYPE_BULK = 'Bulk';
    const TYPE_BULK_QUERY = 'BulkQuery';

    const OPERATION_INSERT = 'insert';
    const OPERATION_UPDATE = 'update';
    const OPERATION_UPSERT = 'upsert';
    const OPERATION_DELETE = 'delete';
    const OPERATION_QUERY = 'query';
    const OPERATION_QUERY_ALL = 'queryAll';

    const JOB_FIELD_ID = 'id';
    const JOB_FIELD_STATE = 'state';
    const JOB_FIELD_EXTERNAL_ID_FIELD_NAME = 'externalIdFieldName';
    const JOB_FIELD_QUERY = 'query';

    const STATE_OPEN = 'Open';
    const STATE_UPLOAD_COMPLETE = 'UploadComplete';
    const STATE_JOB_PROCESS_COMPLETE = 'JobComplete';
    const STATE_ABORTED = 'Aborted';
    const STATE_FAILED = 'Failed';

    const JOB_RESULT_SUCCESSFUL = 'successfulResults';
    const JOB_RESULT_FAILED = 'failedResults';
    const JOB_RESULT_UNPROCESSED = 'unprocessedRecords';
    const JOB_QUERY_RESULT_SUCCESSFUL = 'results';

    const JOB_INGEST_ENDPOINT = 'ingest/';
    const JOB_QUERY_ENDPOINT = 'query/';
    const JOB_ADD_BATCHES_ENDPOINT = 'batches/';

    const JOB_RESULT_PASSED_RESULT_ENDPOINT = self::JOB_RESULT_SUCCESSFUL . '/';
    const JOB_RESULT_FAILED_RESULT_ENDPOINT = self::JOB_RESULT_FAILED . '/';
    const JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT = self::JOB_RESULT_UNPROCESSED . '/';
    const JOB_QUERY_RESULT_SUCCESSFUL_RESULT_ENDPOINT = self::JOB_QUERY_RESULT_SUCCESSFUL . '/';

    /** @var String */
    protected $id;

    /** @var String */
    protected $operation;

    /** @var String */
    protected $object;

    /** @var \Salesforce\ORM\Entity */
    protected $entity;

    /** @var String */
    protected $state;

    /** @var \Salesforce\ORM\EntityFactory */
    protected $entityFactory;

    /** @var \Salesforce\ORM\Mapper */
    protected $mapper;

    /** @var array */
    protected $requestBody = [];

    /** @var string */
    protected $baseUrl = self::JOB_INGEST_ENDPOINT;

    /** @var string */
    protected $successResultUrl = self::JOB_RESULT_PASSED_RESULT_ENDPOINT;

    /** @var string */
    protected $failedResultUrl = self::JOB_RESULT_FAILED_RESULT_ENDPOINT;

    /** @var string */
    protected $unprocessedResultUrl = self::JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT;

    /**
     * Job constructor.
     * @param \Salesforce\ORM\EntityFactory|null $entityFactory
     * @param \Salesforce\ORM\Mapper|null $mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(EntityFactory $entityFactory = null, Mapper $mapper = null)
    {
        $this->entityFactory = $entityFactory ?: new EntityFactory();
        $this->mapper = $mapper ?: new Mapper();
    }

    /**
     * @return String
     */
    public function getId(): String
    {
        return $this->id;
    }

    /**
     * @param String $id
     */
    public function setId(String $id)
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getOperation(): String
    {
        return $this->operation;
    }

    /**
     * @param String $operation
     */
    public function setOperation(String $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return String
     */
    public function getObject(): String
    {
        return $this->object;
    }

    /**
     * @param String $object
     */
    public function setObject(String $object)
    {
        $this->object = $object;
    }

    /**
     * @return \Salesforce\ORM\Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param \Salesforce\ORM\Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return String
     */
    public function getState(): String
    {
        return $this->state;
    }

    /**
     * @param String $state
     */
    public function setState(String $state)
    {
        $this->state = $state;
    }

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    /**
     * @param Mapper $mapper
     */
    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return \Salesforce\ORM\EntityFactory
     */
    public function getEntityFactory(): EntityFactory
    {
        return $this->entityFactory;
    }

    /**
     * @param \Salesforce\ORM\EntityFactory $entityFactory
     */
    public function setEntityFactory(EntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @return array
     */
    public function getRequestBody(): array
    {
        return $this->requestBody;
    }

    /**
     * @param array $requestBody
     */
    public function setRequestBody(array $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getSuccessResultUrl(): string
    {
        return $this->successResultUrl;
    }

    /**
     * @param string $successResultUrl
     */
    public function setSuccessResultUrl(string $successResultUrl)
    {
        $this->successResultUrl = $successResultUrl;
    }

    /**
     * @return string
     */
    public function getFailedResultUrl(): string
    {
        return $this->failedResultUrl;
    }

    /**
     * @param string $failedResultUrl
     */
    public function setFailedResultUrl(string $failedResultUrl)
    {
        $this->failedResultUrl = $failedResultUrl;
    }

    /**
     * @return string
     */
    public function getUnprocessedResultUrl(): string
    {
        return $this->unprocessedResultUrl;
    }

    /**
     * @param string $unprocessedResultUrl
     */
    public function setUnprocessedResultUrl(string $unprocessedResultUrl)
    {
        $this->unprocessedResultUrl = $unprocessedResultUrl;
    }
}
