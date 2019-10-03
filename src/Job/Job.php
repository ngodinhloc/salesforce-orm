<?php
namespace Salesforce\Job;

use Salesforce\ORM\Entity;

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

    /** @var String */
    protected $externalId;

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
     * @return String
     */
    public function getExternalId(): String
    {
        return $this->externalId;
    }

    /**
     * @param String $externalId
     */
    public function setExternalId(String $externalId)
    {
        $this->externalId = $externalId;
    }
}
