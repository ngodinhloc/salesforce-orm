<?php
namespace Salesforce\ORM;

interface ValidationInterface
{
    /**
     * @return \Salesforce\ORM\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;
}
