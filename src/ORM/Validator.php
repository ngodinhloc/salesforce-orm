<?php
namespace Salesforce\ORM;

class Validator
{
    /** @var Mapper */
    protected $mapper;

    /**
     * Validator constructor.
     *
     * @param Mapper|null $mapper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(Mapper $mapper = null)
    {
        $this->mapper = $mapper ?: new Mapper();
    }
}
