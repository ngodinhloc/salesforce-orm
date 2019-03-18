<?php
namespace Salesforce\ORM;

use Salesforce\ORM\Exception\MapperException;

class EntityFactory
{
    /** @var Mapper */
    protected $mapper;

    /**
     * EntityFactory constructor.
     *
     * @param Mapper|null $mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(Mapper $mapper = null)
    {
        $this->mapper = $mapper ?: new Mapper();
    }

    /**
     * Create new entity from array data
     *
     * @param string $class
     * @param array $data data
     * @return Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function new($class, $data)
    {
        if (!$class) {
            throw new MapperException(MapperException::MSG_NO_CLASS_NAME_PROVIDED);
        }

        $object = $this->mapper->object($class);
        $entity = $this->mapper->patch($object, $data);

        return $entity;
    }

    /**
     * Patch entity with data array
     *
     * @param Entity $entity entity
     * @param array $array data
     * @return Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function patch(Entity $entity, $array = [])
    {
        return $this->mapper->patch($entity, $array);
    }
}
