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
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId account id
     * @return Contact
     */
    public function setAccountId(string $accountId = null)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName first name
     * @return Contact
     */
    public function setFirstName(string $firstName = null)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName last name
     * @return Contact
     */
    public function setLastName(string $lastName = null)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email email
     * @return Contact
     */
    public function setEmail(string $email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone phone
     * @return Contact
     */
    public function setPhone(string $phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingCity()
    {
        return $this->mailingCity;
    }

    /**
     * @param string $mailingCity city
     * @return Contact
     */
    public function setMailingCity(string $mailingCity = null)
    {
        $this->mailingCity = $mailingCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingCountry()
    {
        return $this->mailingCountry;
    }

    /**
     * @param string $mailingCountry country
     * @return Contact
     */
    public function setMailingCountry(string $mailingCountry = null)
    {
        $this->mailingCountry = $mailingCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingStreet()
    {
        return $this->mailingStreet;
    }

    /**
     * @param string $mailingStreet street
     * @return Contact
     */
    public function setMailingStreet(string $mailingStreet = null)
    {
        $this->mailingStreet = $mailingStreet;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingState()
    {
        return $this->mailingState;
    }

    /**
     * @param string $mailingState state
     * @return Contact
     */
    public function setMailingState(string $mailingState = null)
    {
        $this->mailingState = $mailingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getMailingPostcode()
    {
        return $this->mailingPostcode;
    }

    /**
     * @param string $mailingPostcode post code
     * @return Contact
     */
    public function setMailingPostcode(string $mailingPostcode = null)
    {
        $this->mailingPostcode = $mailingPostcode;

        return $this;
    }
}
