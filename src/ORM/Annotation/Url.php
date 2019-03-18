<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Salesforce\ORM\ValidationInterface;
use Salesforce\ORM\ValidatorInterface;

/**
 * @Annotation
 */
final class Url extends Annotation implements ValidationInterface
{
    public $value = false;

    /**
     * @return \Salesforce\ORM\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return new \Salesforce\ORM\Validators\Url();
    }
}
