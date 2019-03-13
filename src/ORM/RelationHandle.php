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
     * @param Repository|null $repository repo
     * @param Mapper|null $mapper mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Exception
     */
    public function __construct(Repository $repository = null, Mapper $mapper = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->mapper = $mapper ?: new Mapper();
    }
}
