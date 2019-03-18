<?php
namespace Salesforce\ORM;

interface RelationHandleInterface
{
    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @param \ReflectionProperty $property property
     * @param \Salesforce\ORM\RelationInterface $annotation relation
     * @return \Salesforce\ORM\Entity
     */
    public function handle(Entity &$entity, \ReflectionProperty $property, RelationInterface $annotation);
}
