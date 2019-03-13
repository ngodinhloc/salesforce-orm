<?php
namespace Salesforce\Entity;

use Salesforce\ORM\Annotation as SF;
use Salesforce\ORM\Entity;

/**
 * Salesforce Contact
 *
 * @package Salesforce\Entity
 * @SF\Object(name="Contact")
 */
class Contact extends Entity
{
    /**
     * @var string
     * @SF\Field(name="AccountId")
     * @SF\Required(value=true)
     */
    protected $accountId;


    /**
     * @var string
     * @SF\Field(name="FirstName")
     */
    protected $firstName;

    /**
     * @var string
     * @SF\Field(name="LastName")
     * @SF\Required(value=true)
     */
    protected $lastName;

    /**
     * @var string
     * @SF\Field(name="Email")
     */
    protected $email;

    /**
     * @var string
     * @SF\Field(name="Phone")
     */
    protected $phone;

    /**
     * @var string
     * @SF\Field(name="MailingCity")
     */
    protected $mailingCity;

    /**
     * @var string
     * @SF\Field(name="MailingCountry")
     */
    protected $mailingCountry;

    /**
     * @var string
     * @SF\Field(name="MailingStreet")
     */
    protected $mailingStreet;

    /**
     * @var string
     * @SF\Field(name="MailingState")
     */
    protected $mailingState;

    /**
     * @var string
     * @SF\Field(name="MailingPostalCode")
     */
    protected $mailingPostcode;

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return Contact
     */
    public function setAccountId(string $accountId): Contact
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Contact
     */
    public function setFirstName(string $firstName): Contact
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Contact
     */
    public function setLastName(string $lastName): Contact
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Contact
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Contact
     */
    public function setPhone(string $phone): Contact
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingCity(): string
    {
        return $this->mailingCity;
    }

    /**
     * @param string $mailingCity
     * @return Contact
     */
    public function setMailingCity(string $mailingCity): Contact
    {
        $this->mailingCity = $mailingCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingCountry(): string
    {
        return $this->mailingCountry;
    }

    /**
     * @param string $mailingCountry
     * @return Contact
     */
    public function setMailingCountry(string $mailingCountry): Contact
    {
        $this->mailingCountry = $mailingCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingStreet(): string
    {
        return $this->mailingStreet;
    }

    /**
     * @param string $mailingStreet
     * @return Contact
     */
    public function setMailingStreet(string $mailingStreet): Contact
    {
        $this->mailingStreet = $mailingStreet;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingState(): string
    {
        return $this->mailingState;
    }

    /**
     * @param string $mailingState
     * @return Contact
     */
    public function setMailingState(string $mailingState): Contact
    {
        $this->mailingState = $mailingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingPostcode(): string
    {
        return $this->mailingPostcode;
    }

    /**
     * @param string $mailingPostcode
     * @return Contact
     */
    public function setMailingPostcode(string $mailingPostcode): Contact
    {
        $this->mailingPostcode = $mailingPostcode;

        return $this;
    }
}
