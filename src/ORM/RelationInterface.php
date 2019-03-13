<?php

namespace Salesforce\ORM;

interface RelationInterface
{
    /**
     * @return RelationHandleInterface
     */
    public function getHandler(): RelationHandleInterface;
}
