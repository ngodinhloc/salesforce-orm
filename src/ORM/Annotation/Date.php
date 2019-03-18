<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Salesforce\ORM\Mapper;
use Salesforce\ORM\ValidationInterface;
use Salesforce\ORM\ValidatorInterface;

/**
 * @Annotation
 */
final class Date extends Annotation implements ValidationInterface
{
    public $name = 'Date';
    public $value = false;
    public $format = 'Y-m-d';

    /**
     * @param \Salesforce\ORM\Mapper $mapper
     * @return \Salesforce\ORM\ValidatorInterface
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getValidator(Mapper $mapper): ValidatorInterface
    {
        return new \Salesforce\ORM\Validators\Date($mapper);
    }
}
