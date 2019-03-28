<?php
namespace Salesforce\ORM\Exception;

class EntityException extends \Exception
{
    const MGS_EMPTY_CLASS_NAME = 'Empty class name provided: ';
    const MGS_REQUIRED_PROPERTIES = 'These properties are required: ';
    const MGS_REQUIRED_VALIDATIONS = 'Required validation rules: ';
    const MGS_ID_IS_NOT_PROVIDED = 'Id of the entity is not provided: ';
    const MGS_EMPTY_ENTITY = 'Empy entity provided: ';
}
