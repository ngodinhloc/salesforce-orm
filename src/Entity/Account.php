<?php
namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Salesforce Account
 *
 * @package Salesforce\Entity
 * @SF\Object(name="Account")
 */
class Account extends Entity
{
    /**
     * @var string
     * @SF\Field(name="Name")
     * @SF\Required(value=true)
     */
    protected $name;

    /**
     * @var string
     * @SF\Field(name="Website")
     */
    protected $website;

    /**
     * @var string
     * @SF\Field(name="BillingCity")
     */
    protected $billingCity;

    /**
     * @var string
     * @SF\Field(name="BillingCountry")
     */
    protected $billingCountry;

    /**
     * @var string
     * @SF\Field(name="BillingPostalCode")
     */
    protected $billingPostcode;

    /**
     * @var string
     * @SF\Field(name="BillingState")
     */
    protected $billingState;

    /**
     * @var string
     * @SF\Field(name="BillingStreet")
     */
    protected $billingStreet;

    /**
     * @var string
     * @SF\Field(name="OwnerId")
     */
    protected $ownerId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name name
     * @return Account
     */
    public function setName($name): Account
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website website
     * @return Account
     */
    public function setWebsite($website): Account
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingCity(): string
    {
        return $this->billingCity;
    }

    /**
     * @param string $billingCity city
     * @return Account
     */
    public function setBillingCity($billingCity): Account
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingCountry(): string
    {
        return $this->billingCountry;
    }

    /**
     * @param string $billingCountry country
     * @return Account
     */
    public function setBillingCountry($billingCountry): Account
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingPostcode(): string
    {
        return $this->billingPostcode;
    }

    /**
     * @param string $billingPostcode post code
     * @return Account
     */
    public function setBillingPostcode($billingPostcode): Account
    {
        $this->billingPostcode = $billingPostcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingState(): string
    {
        return $this->billingState;
    }

    /**
     * @param string $billingState state
     * @return Account
     */
    public function setBillingState($billingState): Account
    {
        $this->billingState = $billingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingStreet(): string
    {
        return $this->billingStreet;
    }

    /**
     * @param string $billingStreet street
     * @return Account
     */
    public function setBillingStreet($billingStreet): Account
    {
        $this->billingStreet = $billingStreet;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId owner
     * @return Account
     */
    public function setOwnerId($ownerId): Account
    {
        $this->ownerId = $ownerId;

        return $this;
    }
}
