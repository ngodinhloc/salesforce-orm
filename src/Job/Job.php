<?php
namespace Salesforce\Job;

use Salesforce\Job\Constants\JobConstants;
use Salesforce\ORM\Entity;
use Salesforce\ORM\EntityFactory;
use Salesforce\ORM\Mapper;

class Job
{
    /** @var String */
    protected $id;

    /** @var String */
    protected $operation;

    /** @var String */
    protected $object;

    /** @var Entity */
    protected $entity;

    /** @var String */
    protected $state;

    /** @var String */
    protected $type;

    /** @var \Salesforce\ORM\EntityFactory */
    protected $entityFactory;

    /** @var \Salesforce\ORM\Mapper */
    protected $mapper;

    /** @var array */
    protected $requestBody = [];

    /** @var string */
    protected $baseUrl = JobConstants::JOB_INGEST_ENDPOINT;

    /** @var string */
    protected $successResultUrl = JobConstants::JOB_RESULT_PASSED_RESULT_ENDPOINT;

    /** @var string */
    protected $failedResultUrl = JobConstants::JOB_RESULT_FAILED_RESULT_ENDPOINT;

    /** @var string */
    protected $unprocessedResultUrl = JobConstants::JOB_RESULT_UNPROCESSED_RESULT_ENDPOINT;

    /**
     * Job constructor.
     * @param EntityFactory|null $entityFactory
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
     * @return String
     */
    public function getType(): String
    {
        return $this->type;
    }

    /**
     * @param String $type
     */
    public function setType(String $type)
    {
        $this->type = $type;
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
     * @return EntityFactory
     */
    public function getEntityFactory(): EntityFactory
    {
        return $this->entityFactory;
    }

    /**
     * @param EntityFactory $entityFactory
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
