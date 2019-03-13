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
     * @param string $accountId account id
     * @return Contact
     */
    public function setAccountId($accountId): Contact
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
     * @param string $firstName first name
     * @return Contact
     */
    public function setFirstName($firstName): Contact
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
     * @param string $lastName last name
     * @return Contact
     */
    public function setLastName($lastName): Contact
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
     * @param string $email email
     * @return Contact
     */
    public function setEmail($email): Contact
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
     * @param string $phone phone
     * @return Contact
     */
    public function setPhone($phone): Contact
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
     * @param string $mailingCity city
     * @return Contact
     */
    public function setMailingCity($mailingCity): Contact
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
     * @param string $mailingCountry country
     * @return Contact
     */
    public function setMailingCountry($mailingCountry): Contact
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
     * @param string $mailingStreet street
     * @return Contact
     */
    public function setMailingStreet($mailingStreet): Contact
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
     * @param string $mailingState state
     * @return Contact
     */
    public function setMailingState($mailingState): Contact
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
     * @param string $mailingPostcode post code
     * @return Contact
     */
    public function setMailingPostcode($mailingPostcode): Contact
    {
        $this->mailingPostcode = $mailingPostcode;

        return $this;
    }
}
