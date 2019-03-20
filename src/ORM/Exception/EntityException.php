<?php
namespace Salesforce\ORM\Exception;

class EntityException extends \Exception
{
    const MGS_INVALID_CLASS_NAME = 'Class name not found: ';
    const MGS_REQUIRED_PROPERTIES = 'These properties are required: ';
    const MGS_REQUIRED_VALIDATIONS = 'Required validation rules: ';
    const MGS_ID_IS_NOT_PROVIDED = 'Id of the entity is not provided: ';
}
