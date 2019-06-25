<?php
namespace Salesforce\ORM\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class Field extends Annotation
{
    public $name;
    public $required = false;
    public $protection = true;
}
