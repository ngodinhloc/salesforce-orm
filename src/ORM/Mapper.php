<?php
namespace Salesforce\ORM;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use Salesforce\ORM\Annotation\Field;
use Salesforce\ORM\Annotation\sObject;
use Salesforce\ORM\Exception\MapperException;

class Mapper
{
    /** @var AnnotationReader */
    protected $reader;

    /**
     * Mapper constructor.
     *
     * @param \Doctrine\Common\Annotations\AnnotationReader|null $reader reader
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(AnnotationReader $reader = null)
    {
        $this->reader = $reader ?: new AnnotationReader();
    }

    /**
     * Get object type of entity
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return string
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function getObjectType(Entity $entity)
    {
        $reflectionClass = $this->reflect($entity);
        /* @var sObject $object */
        $object = $this->reader->getClassAnnotation($reflectionClass, sObject::class);
        if (!$object->name) {
            throw new MapperException(MapperException::MSG_OBJECT_TYPE_NOT_FOUND . get_class($entity));
        }

        return $object->name;
    }

    /**
     * Patch object properties with data array
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param array $array array
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function patch(Entity $entity, $array = [])
    {
        $reflectionClass = $this->reflect($entity);
        $properties = $reflectionClass->getProperties();

        $eagerLoad = [];
        $requiredProperties = [];
        $requiredValidations = [];
        foreach ($properties as $property) {
            $annotations = $this->reader->getPropertyAnnotations($property);
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Field) {
                    if (isset($array[$annotation->name])) {
                        $this->setPropertyValue($entity, $property, $array[$annotation->name]);
                    }
                    if ($annotation->required == true) {
                        $requiredProperties[$property->name] = $property;
                    }
                    if ($annotation->protection == true) {
                        $protectionProperties[$annotation->name] = $property;
                    }
                }

                if ($annotation instanceof RelationInterface) {
                    if ($annotation->lazy === false) {
                        $eagerLoad[$property->name] = ['property' => $property, 'annotation' => $annotation];
                    }
                }

                if ($annotation instanceof ValidationInterface) {
                    if ($annotation->value === true) {
                        $requiredValidations[$property->name] = ['property' => $property, 'annotation' => $annotation];
                    }
                }
            }
        }

        if (!empty($eagerLoad)) {
            $this->setPropertyValueByName($entity, Entity::PROPERTY_EAGER_LOAD, $eagerLoad);
        }

        if (!empty($requiredProperties)) {
            $this->setPropertyValueByName($entity, Entity::PROPERTY_REQUIRED_PROPERTIES, $requiredProperties);
        }

        if (!empty($protectionProperties)) {
            $this->setPropertyValueByName($entity, Entity::PROPERTY_PROTECTION_PROPERTIES, $protectionProperties);
        }

        if (!empty($requiredValidations)) {
            $this->setPropertyValueByName($entity, Entity::PROPERTY_REQUIRED_VALIDATIONS, $requiredValidations);
        }

        $this->setPropertyValueByName($entity, Entity::PROPERTY_IS_PATCHED, true);

        return $entity;
    }

    /**
     * Check for entity required properties
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return bool|array
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function checkRequiredProperties(Entity $entity)
    {
        if ($entity->isPatched() !== true) {
            $entity = $this->patch($entity, []);
        }

        if (empty($entity->getRequiredProperties())) {
            return true;
        }

        $missingFields = [];
        /* @var \ReflectionProperty $property */
        foreach ($entity->getRequiredProperties() as $property) {
            if ($this->getPropertyValue($entity, $property) === null) {
                $missingFields[] = $property->name;
            };
        }

        if (empty($missingFields)) {
            return true;
        }

        return $missingFields;
    }

    /**
     * Get none protection data
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param array $data
     * @return array
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function getNoneProtectionData(Entity $entity, array $data = null)
    {
        if ($entity->isPatched() !== true) {
            $entity = $this->patch($entity, []);
        }

        if (empty($data)) {
            $data = $this->toArray($entity);
        }

        if (empty($protectionProperties = $entity->getProtectionProperties())) {
            return $data;
        }

        return array_diff_key($data, $protectionProperties);
    }

    /**
     * @param array|null $data
     * @return bool
     */
    public function checkNoneProtectionData(array $data = null)
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $field => $value) {
            if ($value !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for entity validations
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return bool|array
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function checkRequiredValidations(Entity $entity)
    {
        if ($entity->isPatched() !== true) {
            $entity = $this->patch($entity, []);
        }
        
        if (empty($entity->getRequiredValidations())) {
            return true;
        }

        $validationRules = [];
        /* @var \ReflectionProperty $property */
        foreach ($entity->getRequiredValidations() as $rule) {
            $property = $rule['property'];
            $annotation = $rule['annotation'];

            if ($annotation instanceof ValidationInterface) {
                $validator = $annotation->getValidator($this);
                $check = $validator->validate($entity, $property, $annotation);
                if (!$check) {
                    $validationRules[] = $property->name . ": " . $annotation->name;
                }
            }
        }

        if (empty($validationRules)) {
            return true;
        }

        return $validationRules;
    }

    /**
     * Get array of entity
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @return array
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function toArray(Entity $entity)
    {
        $reflectionClass = $this->reflect($entity);
        $properties = $reflectionClass->getProperties();

        $array = [];
        foreach ($properties as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, Field::class);
            if ($annotation instanceof Field) {
                $array[$annotation->name] = $this->getPropertyValue($entity, $property);
            }
        }

        return $array;
    }

    /**
     * Set property value
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param \ReflectionProperty $property property
     * @param mixed $value value
     * @return void
     */
    public function setPropertyValue(Entity &$entity, \ReflectionProperty $property, $value)
    {
        $property->setAccessible(true);
        $property->setValue($entity, $value);
    }

    /**
     * Get property value
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param \ReflectionProperty $property property
     * @return mixed
     */
    public function getPropertyValue(Entity $entity, \ReflectionProperty $property)
    {
        if ($property instanceof \ReflectionProperty) {
            $property->setAccessible(true);

            return $property->getValue($entity);
        }
    }

    /**
     * Set property value by name
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param string $propertyName name
     * @param mixed $value value
     * @return void
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function setPropertyValueByName(Entity &$entity, string $propertyName, $value)
    {
        $property = $this->getProperty($entity, $propertyName);
        $this->setPropertyValue($entity, $property, $value);
    }

    /**
     * Get property value by name
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param string $propertyName name
     * @return mixed
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function getPropertyValueByName(Entity $entity, string $propertyName)
    {
        $property = $this->getProperty($entity, $propertyName);

        return $this->getPropertyValue($entity, $property);
    }

    /**
     * Get value of a property by field name
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param string $fieldName field name
     * @return \ReflectionProperty|null
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function getPropertyValueByFieldName(Entity $entity, $fieldName)
    {
        $reflectionClass = $this->reflect($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, Field::class);
            if ($annotation instanceof Field && $annotation->name == $fieldName) {
                return $this->getPropertyValue($entity, $property);
            }
        }

        throw new MapperException(MapperException::MSG_NO_FIELD_FOUND . $fieldName);
    }

    /**
     * Get enity property by property name
     *
     * @param \Salesforce\ORM\Entity $entity entity
     * @param string $propertyName name
     * @return bool|\ReflectionProperty
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function getProperty(Entity $entity, string $propertyName)
    {
        $reflectionClass = $this->reflect($entity);
        $properties = $reflectionClass->getProperties();
        /* @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if ($property->name == $propertyName) {
                return $property;
            }
        }

        return false;
    }

    /**
     * Create Entity object from class name
     *
     * @param string $class class name
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function object($class)
    {
        try {
            $object = new $class();
        } catch (\Exception $exception) {
            throw new MapperException(MapperException::MGS_INVALID_CLASS_NAME . $class);
        }

        return $object;
    }

    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @return \ReflectionClass
     * @throws \Salesforce\ORM\Exception\MapperException
     */
    public function reflect(Entity $entity)
    {
        try {
            $reflectionClass = new ReflectionClass(get_class($entity));
            $this->register();
        } catch (\ReflectionException $exception) {
            throw new MapperException(MapperException::MGS_FAILED_TO_CREATE_REFLECT_CLASS . $exception->getMessage());
        }

        return $reflectionClass;
    }

    /**
     * Register annotation classes
     *
     * @return void
     */
    protected function register()
    {
        if (class_exists(AnnotationRegistry::class)) {
            AnnotationRegistry::registerLoader('class_exists');
        }
    }
}
