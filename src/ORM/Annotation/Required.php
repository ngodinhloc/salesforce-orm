<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class Required extends Annotation
{
    public $value = false;
}
