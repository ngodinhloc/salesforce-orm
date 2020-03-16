<?php
namespace Salesforce\ORM\Exception;

use Salesforce\Exception\SalesforceException;

class EntityException extends SalesforceException
{
    const MGS_EMPTY_CLASS_NAME = 'Empty class name provided: ';
    const MGS_REQUIRED_PROPERTIES = 'These properties are required: ';
    const MGS_REQUIRED_VALIDATIONS = 'Required validation rules: ';
    const MGS_ID_IS_NOT_PROVIDED = 'Id of the entity is not provided: ';
    const MGS_EMPTY_ENTITY = 'Empy entity provided: ';
    const MGS_EMPTY_DATA = 'Empty data provided.';
    const MGS_EMPTY_HEADER = 'Empty header provided.';
    const MGS_CSV_ROW_COUNT_MISMATCH = 'CSV Row size are not equal.';
    const MGS_EMPTY_NONE_PROTECTION_DATA = 'There is not any none protection data field provided.';
}
