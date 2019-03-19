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
        $value = $mapper->getPropertyValueByFieldName($entity, $annotation->field);
        $collections = $this->entityManager->findBy($annotation->targetClass, ["{$annotation->targetField} = {$value}"]);
        $mapper->setPropertyValue($entity, $property, $collections);
    }
}
