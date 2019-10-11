<?php

namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Class OpportunityContactRole
 *
 * @package Salesforce\Entity
 * @SF\sObject(name="OpportunityContactRole")
 */
class OpportunityContactRole extends Entity
{
    /**
     * @var string
     * @SF\Field(name="ContactId", required=true)
     */
    protected $contactId;

    /**
     * @var string
     * @SF\Field(name="OpportunityId", required=true)
     */
    protected $opportunityId;

    /**
     * @var bool
     * @SF\Field(name="IsPrimary")
     */
    protected $isPrimary = false;

    /**
     * @var string
     * @SF\Field(name="Role")
     */
    protected $role;

    /**
     * @return string
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * @param string $contactId contact id
     * @return OpportunityContactRole
     */
    public function setContactId(string $contactId = null)
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOpportunityId()
    {
        return $this->opportunityId;
    }

    /**
     * @param string $opportunityId opportunity id
     * @return OpportunityContactRole
     */
    public function setOpportunityId(string $opportunityId = null)
    {
        $this->opportunityId = $opportunityId;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary primary
     * @return OpportunityContactRole
     */
    public function setIsPrimary(bool $isPrimary = null)
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role role
     * @return OpportunityContactRole
     */
    public function setRole(string $role = null)
    {
        $this->role = $role;

        return $this;
    }
}
