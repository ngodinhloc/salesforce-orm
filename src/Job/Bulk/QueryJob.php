<?php
namespace Salesforce\Job\Bulk;

use Salesforce\Job\Constants\JobConstants;
use Salesforce\Job\Exception\JobException;
use Salesforce\Job\Job;
use Salesforce\Job\QueryInterface;

class QueryJob extends Job implements QueryInterface
{
    /** @var string|null */
    protected $query = null;

    /** @var string */
    protected $baseUrl = JobConstants::JOB_QUERY_ENDPOINT;

    /** @var string */
    protected $operation = JobConstants::OPERATION_QUERY;

    /** @var string */
    protected $successResultUrl = JobConstants::JOB_QUERY_RESULT_SUCCESSFUL;

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * @return array
     * @throws \Salesforce\Job\Exception\JobException
     */
    public function getRequestBody(): array
    {
        $requestBody = array_merge($this->requestBody, [JobConstants::JOB_FIELD_QUERY => $this->getQuery()]);
        if (empty($requestBody[JobConstants::JOB_FIELD_QUERY])) {
            throw new JobException(JobException::MSG_FAILED_QUERY_REQUIRED_FOR_QUERY_JOB);
        }

        return $requestBody;
    }
}
