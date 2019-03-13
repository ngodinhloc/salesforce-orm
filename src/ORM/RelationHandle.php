<?php
namespace Salesforce\ORM;

class RelationHandle
{
    /** @var Repository */
    protected $repository;

    /** @var Mapper */
    protected $mapper;

    /**
     * RelationHandle constructor.
     *
     * @param EntityManager|null $entityManager entity manager
     * @param Mapper|null $mapper mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Exception
     */
    public function __construct(EntityManager $entityManager, Mapper $mapper = null)
    {
        $this->repository = new Repository($entityManager);
        $this->mapper = $mapper ?: new Mapper();
    }
}
