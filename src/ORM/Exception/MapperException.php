<?php
namespace Salesforce\ORM\Exception;

class MapperException extends \Exception
{
    const FAILED_TO_CREATE_REFLECT_CLASS = 'Failed to create reflect class: ';
    const OBJECT_TYPE_NOT_FOUND = 'Object type not found. Check class annotation: ';
}
