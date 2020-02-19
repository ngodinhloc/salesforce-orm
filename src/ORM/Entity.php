<?php
namespace Salesforce\ORM;

use Salesforce\ORM\Annotation as SF;

class Entity
{
    const PROPERTY_IS_NEW = "isNew";
    const PROPERTY_IS_PATCHED = "isPatched";
    const PROPERTY_EAGER_LOAD = "eagerLoad";
    const PROPERTY_REQUIRED_PROPERTIES = "requiredProperties";
    const PROPERTY_PROTECTION_PROPERTIES = "protectionProperties";
    const PROPERTY_REQUIRED_VALIDATIONS = "requiredValidations";

    /**
     * @var string
     * @SF\Field(name="Id", protection=true)
     */
    protected $id;

    /**
     * @var bool
     */
    protected $isNew = false;

    /**
     * @var bool
     */
    protected $isPatched = false;

    /**
     * @var array
     */
    protected $eagerLoad;

    /**
     * @var array
     */
    protected $requiredProperties;

    /**
     * @var array
     */
    protected $protectionProperties;

    /**
     * @var array
     */
    protected $requiredValidations;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id id
     * @return Entity
     */
    public function setId(string $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * @return bool|null
     */
    public function isPatched()
    {
        return $this->isPatched;
    }

    /**
     * @return array|null
     */
    public function getEagerLoad()
    {
        return $this->eagerLoad;
    }

    /**
     * @return array|null
     */
    public function getRequiredProperties()
    {
        return $this->requiredProperties;
    }

    /**
     * @return array
     */
    public function getRequiredValidations()
    {
        return $this->requiredValidations;
    }

    /**
     * @return array
     */
    public function getProtectionProperties()
    {
        return $this->protectionProperties;
    }

    /**
     * @param bool $isPatched
     */
    public function setIsPatched(bool $isPatched)
    {
        $this->isPatched = $isPatched;
    }

    /**
     * @param array $eagerLoad
     */
    public function setEagerLoad(array $eagerLoad)
    {
        $this->eagerLoad = $eagerLoad;
    }

    /**
     * @param array $requiredValidations
     */
    public function setRequiredValidations(array $requiredValidations)
    {
        $this->requiredValidations = $requiredValidations;
    }
}
