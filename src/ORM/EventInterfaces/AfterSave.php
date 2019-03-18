<?php
namespace Salesforce\ORM\EventInterfaces;

use Salesforce\ORM\Entity;

interface AfterSave
{
    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @return mixed
     */
    public function afterSave(Entity &$entity);
}
