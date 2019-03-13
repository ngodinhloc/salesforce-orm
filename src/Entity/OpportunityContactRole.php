<?php

namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Class OpportunityContactRole
 *
 * @package Salesforce\Entity
 * @SF\Object(name="OpportunityContactRole")
 */
class OpportunityContactRole extends Entity
{
    /**
     * @var string
     * @SF\Field(name="ContactId")
     * @SF\Required(value=true)
     */
    protected $contactId;

    /**
     * @var string
     * @SF\Field(name="OpportunityId")
     * @SF\Required(value=true)
     */
    protected $opportunityId;

    /**
     * @var bool
     * @SF\Field(name="IsPrimary")
     */
    protected $isPrimary;

    /**
     * @var string
     * @SF\Field(name="Role")
     */
    protected $role;

    /**
     * @return string
     */
    public function getContactId(): string
    {
        return $this->contactId;
    }

    /**
     * @param string $contactId contact id
     * @return OpportunityContactRole
     */
    public function setContactId(string $contactId): OpportunityContactRole
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOpportunityId(): string
    {
        return $this->opportunityId;
    }

    /**
     * @param string $opportunityId opportunity id
     * @return OpportunityContactRole
     */
    public function setOpportunityId(string $opportunityId): OpportunityContactRole
    {
        $this->opportunityId = $opportunityId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary primary
     * @return OpportunityContactRole
     */
    public function setIsPrimary(bool $isPrimary): OpportunityContactRole
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role role
     * @return OpportunityContactRole
     */
    public function setRole(string $role): OpportunityContactRole
    {
        $this->role = $role;

        return $this;
    }
}
