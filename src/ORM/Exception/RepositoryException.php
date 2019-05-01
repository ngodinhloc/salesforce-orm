<?php
namespace Salesforce\ORM\Exception;

use Salesforce\Exception\SalesforceException;

class RepositoryException extends SalesforceException
{
    const MSG_NO_CLASS_NAME_PROVIDED = "No class provided. Please check repository class.";
}
