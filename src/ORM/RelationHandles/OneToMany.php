<?php

namespace Salesforce\ORM\RelationHandles;

use Salesforce\ORM\Entity;
use Salesforce\ORM\RelationHandle;
use Salesforce\ORM\RelationHandleInterface;
use Salesforce\ORM\RelationInterface;

class OneToMany extends RelationHandle implements RelationHandleInterface
{
    /**
     * @param Entity $entity entity
     * @param \ReflectionProperty $property property
     * @param RelationInterface $relation relation
     * @return void
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\ResultException
     * @throws \Exception
     */
    public function handle(Entity &$entity, \ReflectionProperty $property, RelationInterface $relation)
    {
        /* @var \Salesforce\ORM\Annotation\OneToMany $relation */
        $mapper = $this->entityManager->getMapper();
        $collections = $this->entityManager->query($relation->class, ["{$relation->field} = {$entity->getId()}"]);
        $objects = [];
        foreach ($collections as $array) {
            $object = $this->entityManager->object($relation->class);
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
