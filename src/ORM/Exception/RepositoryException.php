<?php
namespace Salesforce\ORM\Exception;

class RepositoryException extends \Exception
{
    const MSG_NO_CLASS_NAME_PROVIDED = "No class provided. Please check repository class.";
}
