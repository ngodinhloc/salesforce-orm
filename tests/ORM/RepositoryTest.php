<?php
namespace SalesforceTest\ORM;

use Salesforce\Entity\Account;
use Salesforce\ORM\EntityManager;
use Salesforce\ORM\Repository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $entityManager;
    /** @var Repository */
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->repository = new Repository($this->entityManager);
    }

    public function testFind()
    {
        $class = "Account";
        $id = "12345";
        $this->entityManager->expects($this->once())->method('find')->with($class, $id);
        $this->repository->setClassName($class)->find($id);
    }

    public function testSave()
    {
        $account = new Account();
        $this->entityManager->expects($this->once())->method('save')->with($account);
        $this->repository->save($account);
    }

    public function testQuery()
    {
        $class = "Account";
        $where = ["Id" => "12345"];
        $this->entityManager->expects($this->once())->method('query');
        $this->repository->setClassName($class)->findBy($where);
    }
}
