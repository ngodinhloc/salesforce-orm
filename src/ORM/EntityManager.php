<?php
namespace Salesforce\ORM;

use Salesforce\Client\Client;
use Salesforce\Client\Config;
use Salesforce\Client\FieldNames;
use Salesforce\ORM\Exception\EntityException;
use Salesforce\ORM\Query\Builder;

/**
 * Class EntityManager
 *
 * @package App\Domain\Marketing\Salesforce
 */
class EntityManager
{
    /* @var Client $salesforceClient */
    protected $salesforceClient;

    /** @var Mapper */
    protected $mapper;

    /**
     * EntityManager constructor.
     *
     * @param array $config client config, must have the following
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     * ]
     * @param Mapper|null $mapper mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \EventFarm\Restforce\RestforceException
     * @throws \Salesforce\Client\Exception\ConfigException
     */
    public function __construct(array $config, Mapper $mapper = null)
    {
        $clientConfig = new Config($config);
        $this->salesforceClient = new Client($clientConfig);
        $this->mapper = $mapper ?: new Mapper();
    }

    /**
     * Create new object from data
     *
     * @param string $class class
     * @param array $data data
     * @return Entity
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function new($class, $data)
    {
        $object = $this->object($class);
        $entity = $this->mapper->patch($object, $data);

        return $entity;
    }

    /**
     * Patch entity with data array
     *
     * @param Entity $entity entity
     * @param array $array array
     * @return Entity patched entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function patch(Entity $entity, $array = [])
    {
        return $this->mapper->patch($entity, $array);
    }

    /**
     * @param string $class class name
     * @param string $id id
     * @return Entity|false patched entity
     * @throws \Salesforce\ORM\EXception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function find($class, $id)
    {
        $object = $this->object($class);
        $objectType = $this->mapper->getObjectType($object);
        $find = $this->salesforceClient->findObject($objectType, $id);

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
     * Save entity
     *
     * @param Entity $entity entity
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function save(Entity &$entity)
    {
        $checkRequiredProperties = $this->mapper->checkRequiredProperties($entity);
        if ($checkRequiredProperties !== true) {
            throw new EntityException(EntityException::MGS_REQUIRED_PROPERTIES . implode(", ", $checkRequiredProperties));
        }

        $objectType = $this->mapper->getObjectType($entity);
        $data = $this->mapper->toArray($entity);

        // unset Id before sending to Salesforce
        if (isset($data[FieldNames::SF_FIELD_ID])) {
            unset($data[FieldNames::SF_FIELD_ID]);
        };

        // If id is set, then update object
        if ($entity->getId()) {
            if ($this->salesforceClient->updateObject($objectType, $entity->getId(), $data)) {
                $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, false);

                return true;
            };
        }
        // id is not set, then create object
        if ($data = $this->salesforceClient->createObject($objectType, $data)) {
            if ($data['success']) {
                $entity->setId($data['id']);
                $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, true);

                return true;
            }
        };

        return false;
    }

    /**
     * Query Salesforces
     *
     * @param string $class class name
     * @param array $conditions conditions
     * @return mixed
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function query($class, $conditions = [])
    {
        $entity = $this->object($class);
        $objectType = $this->mapper->getObjectType($entity);
        $array = $this->mapper->toArray($entity);
        $builder = new Builder();
        $query = $builder->from($objectType)->select(array_keys($array))->where($conditions)->getQuery();

        return $this->salesforceClient->query($query);
    }

    /**
     * Load object in relations
     *
     * @param Entity $entity entity
     * @return Entity
     */
    public function eagerLoad(Entity $entity)
    {
        if (empty($entity->getEagerLoad())) {
            return $entity;
        }
        foreach ($entity->getEagerLoad() as $load) {
            $property = $load['property'];
            $relation = $load['relation'];
            if ($relation instanceof RelationInterface) {
                $handler = $relation->getHandler($this);
                $handler->handle($entity, $property, $relation);
            }
        }

        return $entity;
    }

    /**
     * Create Entity object from class name
     *
     * @param string $class class name
     * @return Entity
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function object($class)
    {
        try {
            $object = new $class();
        } catch (\Exception $exception) {
            throw new EntityException(EntityException::MGS_INVALID_CLASS_NAME . $class);
        }

        return $object;
    }

    /**
     * @param string $class class
     * @return Repository
     * @throws \Exception
     */
    public function createRepository($class)
    {
        $repository = new Repository($this);
        $repository->setClass($class);

        return $repository;
    }

    /**
     * @return Client
     */
    public function getSalesforceClient()
    {
        return $this->salesforceClient;
    }

    /**
     * @param Client $salesforceClient client
     * @return void
     */
    public function setSalesforceClient(Client $salesforceClient)
    {
        $this->salesforceClient = $salesforceClient;
    }

    /**
     * @return Mapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param Mapper $mapper mapper
     * @return void
     */
    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
