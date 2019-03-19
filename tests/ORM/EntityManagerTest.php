<?php

namespace SalesforceTest\ORM;

use PHPUnit\Framework\TestCase;
use Salesforce\Client\Client;
use Salesforce\Client\FieldNames;
use Salesforce\Client\ResponseCodes;
use Salesforce\Entity\Account;
use Salesforce\ORM\EntityManager;
use Salesforce\ORM\Mapper;
use Salesforce\ORM\Query\Builder;

class EntityManagerTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $sfClient;
    /** @var Mapper */
    protected $mapper;
    /** @var EntityManager */
    protected $entityManager;

    public function setUp()
    {
        parent::setUp();
        $this->sfClient = $this->createMock(Client::class);
        $this->mapper = new Mapper();
        $config = ["clientId" => "***", "clientSecret" => "***", "path" => "***", 'username' => '***', "password" => "***", "apiVersion" => "***"];
        $this->entityManager = new EntityManager($config, $this->mapper);
        $this->entityManager->setSalesforceClient($this->sfClient);
    }

    public function testFind()
    {
        $id = "someId";
        $class = Account::class;
        $object = $this->mapper->object($class);
        $objectType = $this->mapper->getObjectType($object);
        $this->sfClient->expects($this->once())->method("findObject")->with($objectType, $id)->willReturn(false);
        $this->entityManager->find($class, $id);
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
        $objectType = $this->mapper->getObjectType($account);
        $data = $this->mapper->toArray($account);
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
        $this->sfClient->expects($this->once())->method("query")->with($query);
        $this->entityManager->findBy(Account::class, $conditions);
    }
}
