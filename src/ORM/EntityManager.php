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
     * @param \Salesforce\ORM\Mapper|null $mapper mapper
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
     * @param string $class class name
     * @param string $id id
     * @return \Salesforce\ORM\Entity|false patched entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function find($class, $id)
    {
        $object = $this->mapper->object($class);
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
        if ($id = $this->salesforceClient->createObject($objectType, $data)) {
            $entity->setId($id);
            $this->mapper->setPropertyValueByName($entity, Entity::PROPERTY_IS_NEW, true);

            return true;
        };

        return false;
    }

    /**
     * Query Salesforces
     *
     * @param string $class class name
     * @param array $conditions conditions
     * @return mixed
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function query($class, $conditions = [])
    {
        $entity = $this->mapper->object($class);
        $objectType = $this->mapper->getObjectType($entity);
        $array = $this->mapper->toArray($entity);
        $builder = new Builder();
        $query = $builder->from($objectType)->select(array_keys($array))->where($conditions)->getQuery();

        return $this->salesforceClient->query($query);
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
     * @param string $class class
     * @return \Salesforce\ORM\Repository
     * @throws \Exception
     */
    public function getRepository($class)
    {
        $repository = new Repository($this);
        $repository->setClass($class);

        return $repository;
    }

    /**
     * @return \Salesforce\Client\Client
     */
    public function getSalesforceClient()
    {
        return $this->salesforceClient;
    }

    /**
     * @param \Salesforce\Client\Client $salesforceClient client
     * @return void
     */
    public function setSalesforceClient(Client $salesforceClient)
    {
        $this->salesforceClient = $salesforceClient;
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
