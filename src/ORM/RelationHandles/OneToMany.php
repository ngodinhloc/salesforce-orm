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
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\ORM\Exception\ResultException
     */
    public function handle(Entity &$entity, \ReflectionProperty $property, RelationInterface $relation)
    {
        /* @var \App\Domain\Marketing\Salesforce\ORM\Annotation\OneToMany $relation */
        $collections = $this->repository->setClass($relation->class)->query(["{$relation->field} = {$entity->getId()}"]);
        $objects = [];
        foreach ($collections as $array) {
            $object = $this->repository->getEntityManager()->object($relation->class);
            $relationEntity = $this->mapper->patch($object, $array);
            if (empty($relationEntity->getEagerLoad())) {
                $objects[] = $relationEntity;
            } else {
                $objects[] = $this->repository->getEntityManager()->eagerLoad($relationEntity);
            }
        }
        $this->mapper->setPropertyValue($entity, $property, $objects);
    }
}
