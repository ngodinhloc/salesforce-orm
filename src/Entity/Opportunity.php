<?php
namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Salesforce Opportunity
 *
 * @package Salesforce\Entity
 * @SF\sObject(name="Opportunity")
 */
class Opportunity extends Entity
{
    /**
     * @var string
     * @SF\Field(name="name")
     * @SF\Required(value=true)
     */
    protected $name;

    /**
     * @var string
     * @SF\Field(name="AccountId")
     * @SF\Required(value=true)
     */
    protected $accountId;

    /**
     * @var string
     * @SF\Field(name="closeDate")
     * @SF\Required(value=true)
     */
    protected $closeDate;

    /**
     * @var string
     * @SF\Field(name="RecordTypeId")
     * @SF\Required(value=true)
     */
    protected $recordTypeId;

    /**
     * @var string
     * @SF\Field(name="StageName")
     * @SF\Required(value=true)
     */
    protected $stage;

    /**
     * @var string
     * @SF\Field(name="OwnerId")
     * @SF\Required(value=true)
     */
    protected $ownerId;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name name
     * @return Opportunity
     */
    public function setName(string $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId account id
     * @return Opportunity
     */
    public function setAccountId(string $accountId = null)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * @param string $closeDate close date
     * @return Opportunity
     */
    public function setCloseDate(string $closeDate = null)
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecordTypeId()
    {
        return $this->recordTypeId;
    }

    /**
     * @param string $recordTypeId record type
     * @return Opportunity
     */
    public function setRecordTypeId(string $recordTypeId = null)
    {
        $this->recordTypeId = $recordTypeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param string $stage stage
     * @return Opportunity
     */
    public function setStage(string $stage = null)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId owner
     * @return Opportunity
     */
    public function setOwnerId(string $ownerId = null)
    {
        $this->ownerId = $ownerId;

        return $this;
    }
}
