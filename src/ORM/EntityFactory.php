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
     * @param \Salesforce\ORM\Mapper|null $mapper
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
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function new(string $class = null, array $data = null)
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
     * @param \Salesforce\ORM\Entity $entity entity
     * @param array $array data
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function patch(Entity $entity = null, array $array = [])
    {
        return $this->mapper->patch($entity, $array);
    }

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    /**
     * @param Mapper $mapper
     */
    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
