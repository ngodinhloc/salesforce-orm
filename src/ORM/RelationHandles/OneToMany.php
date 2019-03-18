<?php

namespace Salesforce\ORM\RelationHandles;

use Salesforce\ORM\Entity;
use Salesforce\ORM\RelationHandle;
use Salesforce\ORM\RelationHandleInterface;
use Salesforce\ORM\RelationInterface;

class OneToMany extends RelationHandle implements RelationHandleInterface
{
    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @param \ReflectionProperty $property property
     * @param \Salesforce\ORM\RelationInterface $annotation relation
     * @return void
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Exception
     */
    public function handle(Entity &$entity, \ReflectionProperty $property, RelationInterface $annotation)
    {
        /* @var \Salesforce\ORM\Annotation\OneToMany $annotation */
        $mapper = $this->entityManager->getMapper();
        $collections = $this->entityManager->query($annotation->class, ["{$annotation->field} = {$entity->getId()}"]);
        $objects = [];
        foreach ($collections as $array) {
            $object = $mapper->object($annotation->class);
            $relationEntity = $mapper->patch($object, $array);
            if (empty($relationEntity->getEagerLoad())) {
                $objects[] = $relationEntity;
            } else {
                $objects[] = $this->entityManager->eagerLoad($relationEntity);
            }
        }
        $mapper->setPropertyValue($entity, $property, $objects);
    }
}
