<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
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
     * @return RelationHandleInterface
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getHandler(): RelationHandleInterface
    {
        return new \Salesforce\ORM\RelationHandles\OneToMany();
    }
}
