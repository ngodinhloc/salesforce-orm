<?php
namespace Salesforce\ORM;

use Salesforce\ORM\EventInterfaces\AfterSave;
use Salesforce\ORM\EventInterfaces\BeforeSave;
use Salesforce\ORM\Exception\MapperException;
use Salesforce\ORM\Exception\RepositoryException;

class Repository
{
    /* @var string $className class name */
    protected $className;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * Repository constructor.
     *
     * @param \Salesforce\ORM\EntityManager|null $entityManager entity manager
     * @throws \Exception
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Find object by id
     *
     * @param string $id id
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function find(string $id)
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->find($this->className, $id);
    }

    /**
     * Find objects on by conditions
     *
     * @param array $conditions conditions
     * @param int $limit
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findBy($conditions = [], $limit = null)
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->findBy($this->className, $conditions, $limit);
    }

    /**
     * Find all object of this class
     *
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findAll()
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->findAll($this->className);
    }

    /**
     * Save entity
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function save(Entity &$entity)
    {
        if ($this instanceof BeforeSave) {
            $this->beforeSave($entity);
        }
        $result = $this->entityManager->save($entity);
        if ($result) {
            if ($this instanceof AfterSave) {
                $this->afterSave($entity);
            }
        }

        return $result;
    }

    /**
     * Create new entity from array data
     *
     * @param array $data data
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function new($data)
    {
        if (!$this->className) {
            throw new MapperException(MapperException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        $object = $this->entityManager->getMapper()->object($this->className);
        $entity = $this->entityManager->getMapper()->patch($object, $data);

        return $entity;
    }

    /**
     * Patch entity with data array
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param array $array data
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function patch(Entity $entity, $array = [])
    {
        return $this->entityManager->getMapper()->patch($entity, $array);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className class name
     * @return \Salesforce\ORM\Repository
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return \Salesforce\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param \Salesforce\ORM\EntityManager $entityManager entity manager
     * @return \Salesforce\ORM\Repository
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }
}
