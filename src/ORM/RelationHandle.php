<?php
namespace Salesforce\ORM;

class RelationHandle
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * RelationHandle constructor.
     *
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
