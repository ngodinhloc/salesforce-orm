<?php
namespace Salesforce\ORM\Exception;

class EntityException extends \Exception
{
    const MGS_INVALID_CLASS_NAME = 'Class name not found: ';
    const MGS_REQUIRED_PROPERTIES = 'These properties are required: ';
}
