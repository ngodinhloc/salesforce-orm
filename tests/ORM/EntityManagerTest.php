<?php
namespace SalesforceTest\ORM;

use PHPUnit\Framework\TestCase;
use Salesforce\Client\Client;
use Salesforce\Client\Connection;
use Salesforce\Client\ResponseCodes;
use Salesforce\Entity\Account;
use Salesforce\ORM\EntityManager;
use Salesforce\ORM\FieldNames;
use Salesforce\ORM\Mapper;

class EntityManagerTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $sfClient;
    /** @var Connection */
    protected $connection;
    /** @var Mapper */
    protected $mapper;
    /** @var EntityManager */
    protected $entityManager;

    public function setUp()
    {
        parent::setUp();
        $config = ["clientId" => "***", "clientSecret" => "***", "path" => "***", 'username' => '***', "password" => "***", "apiVersion" => "***", 'apexEndPoint' => 'http://localhost/apex'];
        $this->connection = new Connection($config);
        $this->sfClient = $this->createMock(Client::class);
        $this->connection->setClient($this->sfClient);
        $this->mapper = new Mapper();
        $this->entityManager = new EntityManager($this->connection, $this->mapper);
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
        $data = [
            'Name' => 'Ken',
            'Website' => null,
            'BillingCity' => null,
            'BillingCountry' => null,
            'BillingPostalCode' => null,
            'BillingState' => null,
            'BillingStreet' => null,
            'OwnerId' => null,
        ];
        $this->sfClient->expects($this->once())->method("createObject")->with($objectType, $data);
        $this->entityManager->save($account);
    }
}
