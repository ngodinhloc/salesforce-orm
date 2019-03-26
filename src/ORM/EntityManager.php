<?php
namespace Salesforce\ORM;

use Salesforce\Client\Connection;
use Salesforce\Event\EventDispatcherInterface;
use Salesforce\ORM\Exception\EntityException;
use Salesforce\ORM\Query\Builder;

/**
 * Class EntityManager
 *
 * @package App\Domain\Marketing\Salesforce
 */
class EntityManager
{
    /** @var Connection */
    protected $connection;

    /** @var Mapper */
    protected $mapper;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * EntityManager constructor.
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
     * @param string $className class name
     * @param string $id id
     * @param bool $lazy
     * @return \Salesforce\ORM\Entity|false patched entity
     * @throws Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function find(string $className = null, string $id = null, bool $lazy = false)
    {
        $object = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($object);
        $find = $this->connection->getClient()->findObject($objectType, $id);

        if (!$find) {
            return $find;
        }

        $entity = $this->mapper->patch($object, $find);

        if ($lazy == true) {
            return $entity;
        }
        // No eager loading
        if (empty($entity->getEagerLoad())) {
            return $entity;
        }

        // eager loading
        return $this->eagerLoad($entity);
    }

    /**
     * Query objects by conditions
     *
     * @param string $className class name
     * @param array $conditions conditions
     * @param int|null $limit
     * @param bool $lazy
     * @return array collections of objects
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findBy(string $className = null, array $conditions = [], $limit = null, $lazy = false)
    {
        $entity = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($entity);
        $array = $this->mapper->toArray($entity);
        $builder = new Builder();
        $query = $builder->from($objectType)->select(array_keys($array))->where($conditions)->limit($limit)->getQuery();
        $result = $this->connection->getClient()->query($query);
        $collections = $this->resultToCollection($result, $className, $lazy);

        return $collections;
    }

    /**
     * Find all object of a class name
     *
     * @param string $className class
     * @param bool $lazy lazy loading
     * @return array|bool
     * @throws Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findAll(string $className = null, bool $lazy = true)
    {
        $entity = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($entity);
        $array = $this->mapper->toArray($entity);
        $builder = new Builder();
        $query = $builder->from($objectType)->select(array_keys($array))->getQuery();
        $result = $this->connection->getClient()->query($query);
        $collections = $this->resultToCollection($result, $className, $lazy);

        return $collections;
    }

    /**
     * Count the total number of object
     *
     * @param $className
     * @return int|false
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function count(string $className = null)
    {
        $entity = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($entity);
        $query = "SELECT COUNT(Id) FROM {$objectType}";
        $result = $this->connection->getClient()->query($query);
        if ($result) {
            return $result[0]['expr0'];
        }

        return false;
    }

    /**
     * Save entity
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function save(Entity &$entity)
    {
        $checkRequiredProperties = $this->mapper->checkRequiredProperties($entity);
        if ($checkRequiredProperties !== true) {
            throw new EntityException(EntityException::MGS_REQUIRED_PROPERTIES . implode(", ", $checkRequiredProperties));
        }

        $checkRequiredValidations = $this->mapper->checkRequiredValidations($entity);
        if ($checkRequiredValidations !== true) {
            throw new EntityException(EntityException::MGS_REQUIRED_VALIDATIONS . implode(", ", $checkRequiredValidations));
        }

        $objectType = $this->mapper->getObjectType($entity);
        $data = $this->mapper->toArray($entity);

        /** unset Id before sending to Salesforce */
        if (isset($data[FieldNames::SF_FIELD_ID])) {
            unset($data[FieldNames::SF_FIELD_ID]);
        };

        /** If id is set, then update object */
        if ($entity->getId()) {
            if ($this->connection->getClient()->updateObject($objectType, $entity->getId(), $data)) {
                $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, false);

                return true;
            };
        }
        /** id is not set, then create object */
        if ($id = $this->connection->getClient()->createObject($objectType, $data)) {
            $entity->setId($id);
            $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, true);
            /** dispatch event if EventDispatcher is provided */
            if ($this->eventDispatcher) {
                $this->eventDispatcher->dispatchEvent(EventDispatcherInterface::ENTITY_AFTER_SAVE_EVENT, [], $entity);
            }

            return true;
        };

        return false;
    }

    /**
     * Update entity : allow to update entity with array of data
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param array $data [fieldName => value]
     * @return bool
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function update(Entity &$entity, array $data = [])
    {
        if (!$entity->getId()) {
            throw new EntityException(EntityException::MGS_ID_IS_NOT_PROVIDED);
        }
        if (empty($data)) {
            return true;
        }

        $entity = $this->mapper->patch($entity, $data);
        $checkRequiredValidations = $this->mapper->checkRequiredValidations($entity);
        if ($checkRequiredValidations !== true) {
            throw new EntityException(EntityException::MGS_REQUIRED_VALIDATIONS . implode(", ", $checkRequiredValidations));
        }

        $objectType = $this->mapper->getObjectType($entity);
        /** unset Id before sending to Salesforce */
        if (isset($data[FieldNames::SF_FIELD_ID])) {
            unset($data[FieldNames::SF_FIELD_ID]);
        };

        if ($this->connection->getClient()->updateObject($objectType, $entity->getId(), $data)) {
            $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, false);

            return true;
        };

        return false;
    }

    /**
     * Load object in relations
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return \Salesforce\ORM\Entity
     */
    public function eagerLoad(Entity $entity)
    {
        if (empty($entity->getEagerLoad())) {
            return $entity;
        }
        foreach ($entity->getEagerLoad() as $load) {
            $property = $load['property'];
            $annotation = $load['annotation'];
            if ($annotation instanceof RelationInterface) {
                $handler = $annotation->getHandler($this);
                $handler->handle($entity, $property, $annotation);
            }
        }

        return $entity;
    }

    /**
     * @param array $result
     * @param string $className
     * @param bool $lazy
     * @return array
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function resultToCollection(array $result = [], string $className = null, bool $lazy = true)
    {
        $collections = [];
        if (!empty($result)) {
            foreach ($result as $item) {
                $object = $this->mapper->object($className);
                $relationEntity = $this->mapper->patch($object, $item);
                if ($lazy == true) {
                    $collections[] = $relationEntity;
                } else {
                    if (empty($relationEntity->getEagerLoad())) {
                        $collections[] = $relationEntity;
                    } else {
                        $collections[] = $this->eagerLoad($relationEntity);
                    }
                }
            }
        }

        return $collections;
    }

    /**
     * @param string $class class
     * @return \Salesforce\ORM\Repository
     * @throws \Exception
     */
    public function getRepository(string $class = null)
    {
        $repository = new Repository($this);
        $repository->setClassName($class);

        return $repository;
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
     * @return \Salesforce\ORM\EntityManager
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
     * @return void
     */
    public function setMapper(Mapper $mapper = null)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return EntityManager
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
