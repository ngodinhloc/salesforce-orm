<?php

namespace Salesforce\ORM;

interface RelationInterface
{
    /**
     * @param EntityManager $entityManager entity manager
     * @return RelationHandleInterface
     */
    public function getHandler(EntityManager $entityManager): RelationHandleInterface;
}
