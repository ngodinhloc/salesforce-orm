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
     * @param bool $lazy lazy loading relations
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function find(string $id, $lazy = false)
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->find($this->className, $id, $lazy);
    }

    /**
     * Find objects on by conditions
     *
     * @param array $conditions conditions
     * @param array $orders order
     * @param int $limit
     * @param bool $lazy lazy loading relations
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function findBy(array $conditions = null, array $orders = null, int $limit = null, bool $lazy = false)
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->findBy($this->className, $conditions, $orders, $limit, $lazy);
    }

    /**
     * Find all object of this class
     *
     * @param array $orders order
     * @param bool $lazy lazy loading relations
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function findAll(array $orders = null, bool $lazy = true)
    {
        if (!$this->className) {
            throw new RepositoryException(RepositoryException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        return $this->entityManager->findAll($this->className, $orders, $lazy);
    }

    /**
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
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
