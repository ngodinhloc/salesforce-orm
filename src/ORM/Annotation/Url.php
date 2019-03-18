<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Salesforce\ORM\Mapper;
use Salesforce\ORM\ValidationInterface;
use Salesforce\ORM\ValidatorInterface;

/**
 * @Annotation
 */
final class Url extends Annotation implements ValidationInterface
{
    public $name = 'Url';
    public $value = false;

    /**
     * @param \Salesforce\ORM\Mapper $mapper
     * @return \Salesforce\ORM\ValidatorInterface
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getValidator(Mapper $mapper): ValidatorInterface
    {
        return new \Salesforce\ORM\Validators\Url($mapper);
    }
}
