<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Salesforce\ORM\EntityManager;
use Salesforce\ORM\RelationHandleInterface;
use Salesforce\ORM\RelationInterface;

/**
 * @Annotation
 */
class OneToMany extends Annotation implements RelationInterface
{
    public $name;
    public $field;
    public $class;
    public $lazy = true;

    /**
     * @param EntityManager $entityManager entity manager
     * @return RelationHandleInterface
     */
    public function getHandler(EntityManager $entityManager): RelationHandleInterface
    {
        return new \Salesforce\ORM\RelationHandles\OneToMany($entityManager);
    }
}
