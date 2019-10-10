<?php
namespace Salesforce\Job\Bulk;

use Salesforce\Job\BulkImportInterface;
use Salesforce\Job\Constants\JobConstants;
use Salesforce\Job\Exception\JobException;
use Salesforce\Job\Job;
use Salesforce\Job\JobInterface;
use Salesforce\ORM\Exception\EntityException;

class UpsertJob extends Job implements JobInterface, BulkImportInterface
{
    /** @var array */
    protected $csvData;

    /** @var String */
    protected $externalId;

    /** @var string */
    protected $operation = JobConstants::OPERATION_UPSERT;

    /**
     * @return bool
     * @throws EntityException
     * @throws JobException
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function validate(): bool
    {
        $data = $this->getCsvData();

        if (empty($data) || count($data) < 2) {
            throw new  JobException(JobException::MSG_JOB_DATA_MISSING);
        }

        if (empty($this->getExternalId())) {
            throw new JobException(JobException::MSG_EXTERNAL_ID_FIELD_IS_REQUIRED);
        }

        $header = array_shift($data);

        $rowSize = count($header);

        foreach($data as $row) {
            if (count($row) !== $rowSize) {
                throw new EntityException(EntityException::MGS_CSV_ROW_COUNT_MISMATCH);
            }
            $row = array_combine($header, $row);

            $entity = $this->entityFactory->new(get_class($this->getEntity()), $row);

            if ($entity->isPatched() !== true) {
                $entity = $this->mapper->patch($entity, []);
            }

            if ($entity->getId()) {
                throw new JobException(JobException::MSG_UPSERT_DATA_CANNOT_HAVE_ID_ASSIGNED);
            }

            $checkRequiredProperties = $this->mapper->checkRequiredProperties($entity);
            if ($checkRequiredProperties !== true) {
                throw new EntityException(EntityException::MGS_REQUIRED_PROPERTIES . implode(", ", $checkRequiredProperties));
            }

            $checkRequiredValidations = $this->mapper->checkRequiredValidations($entity);
            if ($checkRequiredValidations !== true) {
                throw new EntityException(EntityException::MGS_REQUIRED_VALIDATIONS . implode(", ", $checkRequiredValidations));
            }

            $data = $this->mapper->getNoneProtectionData($entity);
            if (!$this->mapper->checkNoneProtectionData($data)) {
                throw new EntityException(EntityException::MGS_EMPTY_NONE_PROTECTION_DATA);
            }
        }

        return true;
    }

    /**
     * @return String
     */
    public function getOperation(): String
    {
        return $this->operation;
    }

    /**
     * @return String
     */
    public function getExternalId(): String
    {
        return $this->externalId;
    }

    /**
     * @param String $externalId
     */
    public function setExternalId(String $externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return array
     */
    public function getCsvData(): array
    {
        return $this->csvData;
    }

    /**
     * @param array $csvData
     */
    public function setCsvData(array $csvData)
    {
        $this->csvData = $csvData;
    }

    /**
     * @return array
     */
    public function getRequestBody(): array
    {
        return $this->requestBody ?: [JobConstants::JOB_FIELD_EXTERNAL_ID_FIELD_NAME => $this->getExternalId()];
    }

}