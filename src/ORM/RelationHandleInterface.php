<?php
namespace Salesforce\ORM;

interface RelationHandleInterface
{
    /**
     * @param Entity $entity entity
     * @param \ReflectionProperty $property property
     * @param RelationInterface $annotation relation
     * @return Entity
     */
    public function handle(Entity &$entity, \ReflectionProperty $property, RelationInterface $annotation);
}
