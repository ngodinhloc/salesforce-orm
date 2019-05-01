<?php
namespace Salesforce\Cache\Exception;

use Salesforce\Exception\SalesforceException;

class CacheException extends SalesforceException
{
    const MSG_MISSING_CACHE_CONFIGURATION = 'Missing cache configurations: ';
    const MSG_INVALID_CACHE_DIR = 'Invalid cache dir: ';
}
