<?php
namespace Salesforce\ORM\Exception;

use Salesforce\Exception\SalesforceException;

class MapperException extends SalesforceException
{
    const MGS_FAILED_TO_CREATE_REFLECT_CLASS = 'Failed to create reflect class: ';
    const MSG_OBJECT_TYPE_NOT_FOUND = 'Object type not found. Check class annotation: ';
    const MGS_INVALID_CLASS_NAME = 'Class name not found: ';
    const MSG_NO_CLASS_NAME_PROVIDED = "No class provided. Please check repository class.";
    const MSG_NO_FIELD_FOUND = "No properties has been map for this field: ";
}
