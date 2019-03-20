<?php
namespace Salesforce\ORM;

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
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function count()
    {
        return $this->entityManager->count($this->className);
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
