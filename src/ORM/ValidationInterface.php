<?php
namespace Salesforce\ORM;

interface ValidationInterface
{
    /**
     * @param \Salesforce\ORM\Mapper $mapper
     * @return \Salesforce\ORM\ValidatorInterface
     */
    public function getValidator(Mapper $mapper): ValidatorInterface;
}
