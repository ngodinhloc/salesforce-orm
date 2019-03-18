<?php
namespace Salesforce\ORM\Exception;

class MapperException extends \Exception
{
    const FAILED_TO_CREATE_REFLECT_CLASS = 'Failed to create reflect class: ';
    const OBJECT_TYPE_NOT_FOUND = 'Object type not found. Check class annotation: ';
    const MGS_INVALID_CLASS_NAME = 'Class name not found: ';
    const MSG_NO_CLASS_NAME_PROVIDED = "No class provided. Please check repository class.";
}
