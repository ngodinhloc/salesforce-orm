<?php
namespace Salesforce\ORM;

use Salesforce\ORM\EventInterfaces\AfterSave;
use Salesforce\ORM\EventInterfaces\BeforeSave;
use Salesforce\ORM\Exception\RepositoryException;

class Repository
{
    /* @var string $class class name */
    protected $class;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * Repository constructor.
     *
     * @param EntityManager|null $entityManager entity manager
     * @throws \Exception
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $id id
     * @return Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function find(string $id)
    {
        if (!$this->class) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->find($this->class, $id);
    }

    /**
     * Save entity to Salesforce
     *
     * @param Entity $entity entity
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
     * Query objects on Salesforce
     *
     * @param array $conditions conditions
     * @return mixed
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function query($conditions = [])
    {
        if (!$this->class) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->query($this->class, $conditions);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class class name
     * @return Repository
     */
    public function setClass(string $class): Repository
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager entity manager
     * @return Repository
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }
}
