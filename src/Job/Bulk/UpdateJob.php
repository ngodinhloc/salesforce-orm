<?php
namespace Salesforce\Job\Bulk;

use Salesforce\Job\BulkImportInterface;
use Salesforce\Job\Constants\JobConstants;
use Salesforce\Job\Exception\JobException;
use Salesforce\Job\Job;
use Salesforce\Job\JobInterface;
use Salesforce\ORM\Exception\EntityException;

class UpdateJob extends Job implements JobInterface, BulkImportInterface
{
    /** @var array */
    protected $csvData;

    /** @var string */
    protected $operation = JobConstants::OPERATION_UPDATE;
    /**
     * @return bool
     * @throws \Salesforce\ORM\Exception\EntityException
     * @throws \Salesforce\Job\Exception\JobException
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function validate(): bool
    {
        $data = $this->getCsvData();

        if (empty($data) || count($data) < 2) {
            throw new  JobException(JobException::MSG_JOB_DATA_MISSING);
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

            if (!$entity->getId()) {
                throw new EntityException(EntityException::MGS_ID_IS_NOT_PROVIDED);
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
}
