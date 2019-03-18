<?php

namespace Salesforce\ORM;

interface RelationInterface
{
    /**
     * @param \Salesforce\ORM\EntityManager $entityManager entity manager
     * @return \Salesforce\ORM\RelationHandleInterface
     */
    public function getHandler(EntityManager $entityManager): RelationHandleInterface;
}
