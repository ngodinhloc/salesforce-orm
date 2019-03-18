<?php
namespace Salesforce\ORM\EventInterfaces;

use Salesforce\ORM\Entity;

interface BeforeSave
{
    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @return mixed
     */
    public function beforeSave(Entity &$entity);
}
