<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Salesforce\ORM\ValidationInterface;
use Salesforce\ORM\ValidatorInterface;

/**
 * @Annotation
 */
final class Date extends Annotation implements ValidationInterface
{
    public $value = false;
    public $format = 'Y-m-d';

    /**
     * @return \Salesforce\ORM\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return new \Salesforce\ORM\Validators\Date();
    }
}
