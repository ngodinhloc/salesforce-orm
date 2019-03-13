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
     * @param string $name
     * @return Account
     */
    public function setName(string $name): Account
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
     * @param string $website
     * @return Account
     */
    public function setWebsite(string $website): Account
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
     * @param string $billingCity
     * @return Account
     */
    public function setBillingCity(string $billingCity): Account
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
     * @param string $billingCountry
     * @return Account
     */
    public function setBillingCountry(string $billingCountry): Account
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
     * @param string $billingPostcode
     * @return Account
     */
    public function setBillingPostcode(string $billingPostcode): Account
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
     * @param string $billingState
     * @return Account
     */
    public function setBillingState(string $billingState): Account
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
     * @param string $billingStreet
     * @return Account
     */
    public function setBillingStreet(string $billingStreet): Account
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
     * @param string $ownerId
     * @return Account
     */
    public function setOwnerId(string $ownerId): Account
    {
        $this->ownerId = $ownerId;

        return $this;
    }
}
