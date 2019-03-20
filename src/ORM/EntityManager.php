<?php
namespace Salesforce\ORM;

use Salesforce\Client\Connection;
use Salesforce\Client\FieldNames;
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
     * @return \Salesforce\ORM\Entity|false patched entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function find($className, $id)
    {
        $object = $this->mapper->object($className);
        $objectType = $this->mapper->getObjectType($object);
        $find = $this->connection->getClient()->findObject($objectType, $id);

        if (!$find) {
            return $find;
        }

        $entity = $this->mapper->patch($object, $find);
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
    public function findBy($className, $conditions = [], $limit = null, $lazy = false)
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
    public function findAll($className, $lazy = true)
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
    public function count($className)
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
    public function resultToCollection($result, $className, $lazy = true)
    {
        $collections = [];
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

        return $collections;
    }

    /**
     * @param string $class class
     * @return \Salesforce\ORM\Repository
     * @throws \Exception
     */
    public function getRepository($class)
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
    public function setConnection(Connection $connection)
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
    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
