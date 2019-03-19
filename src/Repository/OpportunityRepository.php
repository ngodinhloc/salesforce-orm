<?php
namespace Salesforce\Repository;

use Salesforce\Entity\Opportunity;
use Salesforce\ORM\Repository;

class OpportunityRepository extends Repository
{
    protected $className = Opportunity::class;
}
