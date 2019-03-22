<?php
namespace Salesforce\Cache\Exception;

class FileCacheException extends CacheException
{
    const ERROR_FAILED_TO_PUT_CONTENT = "Failed to put file content";
    const ERROR_FAILED_TO_GET_CONTENT = "Failed to get file content";
}
