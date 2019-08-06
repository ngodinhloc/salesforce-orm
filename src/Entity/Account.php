<?php
namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Salesforce Account
 *
 * @package Salesforce\Entity
 * @SF\sObject(name="Account")
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name name
     * @return Account
     */
    public function setName(string $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website website
     * @return Account
     */
    public function setWebsite(string $website = null)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * @param string $billingCity city
     * @return Account
     */
    public function setBillingCity(string $billingCity = null)
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    /**
     * @param string $billingCountry country
     * @return Account
     */
    public function setBillingCountry(string $billingCountry = null)
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->billingPostcode;
    }

    /**
     * @param string $billingPostcode post code
     * @return Account
     */
    public function setBillingPostcode(string $billingPostcode = null)
    {
        $this->billingPostcode = $billingPostcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingState()
    {
        return $this->billingState;
    }

    /**
     * @param string $billingState state
     * @return Account
     */
    public function setBillingState(string $billingState = null)
    {
        $this->billingState = $billingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingStreet()
    {
        return $this->billingStreet;
    }

    /**
     * @param string $billingStreet street
     * @return Account
     */
    public function setBillingStreet(string $billingStreet = null)
    {
        $this->billingStreet = $billingStreet;

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
     * @return Account
     */
    public function setOwnerId(string $ownerId = null)
    {
        $this->ownerId = $ownerId;

        return $this;
    }
}
