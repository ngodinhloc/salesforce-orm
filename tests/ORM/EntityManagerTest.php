<?php

namespace SalesforceTest\ORM;

use PHPUnit\Framework\TestCase;
use Salesforce\Client\FieldNames;
use Salesforce\Client\ResponseCodes;
use Salesforce\Client\Client;
use Salesforce\Entity\Account;
use Salesforce\ORM\EntityManager;
use Salesforce\ORM\Mapper;
use Salesforce\ORM\Query\Builder;

class EntityManagerTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $sfClient;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mapper;
    /** @var EntityManager */
    protected $entityManager;

    public function setUp()
    {
        parent::setUp();
        $this->sfClient = $this->createMock(Client::class);
        $this->mapper = $this->createMock(Mapper::class);
        $this->entityManager = new EntityManager($this->sfClient, $this->mapper);
    }

    public function testFind()
    {
        $id = "someId";
        $class = Account::class;
        $this->sfClient->expects($this->once())->method("getObject");
        $this->mapper->expects($this->once())->method("getObjectType")->willReturn("Account");
        $this->mapper->expects($this->once())->method("patch")->willReturn(new Account());
        $this->entityManager->find($class, $id);
    }

    public function testNew()
    {
        $class = Account::class;
        $data = ["Id" => "123456", "Name" => "Ken"];
        $this->mapper->expects($this->once())->method("patch")->with(new $class(), $data)->willReturn(new Account());
        $this->entityManager->new($class, $data);
    }

    public function testUpdate()
    {
        $account = new Account();
        $account->setName("Ken");
        $account->setId("12345");
        $mapper = new Mapper();
        $this->entityManager->setMapper($mapper);
        $objectType = $mapper->getObjectType($account);
        $data = $mapper->toArray($account);

        if (isset($data[FieldNames::SF_FIELD_ID])) {
            unset($data[FieldNames::SF_FIELD_ID]);
        };

        $this->sfClient->expects($this->once())->method("updateObject")->with($objectType, $account->getId(), $data)->willReturn(ResponseCodes::HTTP_NO_CONTENT);
        $this->entityManager->save($account);
    }

    public function testSave()
    {
        $account = new Account();
        $account->setName("Ken");
        $mapper = new Mapper();
        $this->entityManager->setMapper($mapper);
        $objectType = $mapper->getObjectType($account);
        $data = $mapper->toArray($account);
        $this->sfClient->expects($this->once())->method("createObject")->with($objectType, $data);
        $this->entityManager->save($account);
    }

    public function testQuery()
    {
        $account = new Account();
        $conditions = ["Id = 12345"];
        $mapper = new Mapper();
        $this->entityManager->setMapper($mapper);
        $objectType = $mapper->getObjectType($account);
        $array = $mapper->toArray($account);
        $builder = new Builder();
        $query = $builder->from($objectType)->select(array_keys($array))->where($conditions)->getQuery();
        $this->sfClient->expects($this->once())->method("__call")->with('query', [$query]);
        $this->entityManager->query(Account::class, $conditions);
    }

    public function testPatch()
    {
        $account = new Account();
        $data = ["Id" => "12345", "Name" => "Ken"];
        $this->mapper->expects($this->once())->method("patch")->with($account, $data)->willReturn(new Account());
        $this->entityManager->patch($account, $data);
    }
}
