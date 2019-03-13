<?php
namespace Salesforce\ORM\EventInterfaces;

use Salesforce\ORM\Entity;

interface AfterSave
{
    /**
     * @param Entity $entity entity
     * @return mixed
     */
    public function afterSave(Entity &$entity);
}
