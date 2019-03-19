<?php

namespace Salesforce\Repository;

use Salesforce\Entity\Account;
use Salesforce\ORM\Entity;
use Salesforce\ORM\EventInterfaces\AfterSave;
use Salesforce\ORM\EventInterfaces\BeforeSave;
use Salesforce\ORM\Repository;

class AccountRepository extends Repository implements BeforeSave, AfterSave
{
    protected $className = Account::class;

    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @return mixed
     */
    public function afterSave(Entity &$entity)
    {
        // TODO: Implement afterSave() method.
    }

    /**
     * @param \Salesforce\ORM\Entity $entity entity
     * @return mixed
     */
    public function beforeSave(Entity &$entity)
    {
        // TODO: Implement beforeSave() method.
    }
}
