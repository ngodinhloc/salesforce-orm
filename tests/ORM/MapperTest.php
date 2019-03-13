<?php
namespace SalesforceTest\ORM;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Salesforce\Entity\Account;
use Salesforce\ORM\Mapper;

class MapperTest extends TestCase
{
    protected $reader;
    /** @var Mapper */
    protected $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->reader = new AnnotationReader();
        $this->mapper = new Mapper($this->reader);
    }

    public function testGetObjectType()
    {
        $account = new Account();
        $name = $this->mapper->getObjectType($account);
        $this->assertEquals($name, "Account");
    }

    public function testPatch()
    {
        $account = new Account();
        $data = ["Id" => "12346", "Name" => "Ken"];
        $account = $this->mapper->patch($account, $data);
        $this->assertEquals($account->getId(), $data["Id"]);
        $this->assertEquals($account->getName(), $data["Name"]);
    }

    public function testToArray()
    {
        $account = new Account();
        $data = ["Id" => "12346", "Name" => "Ken"];
        $this->mapper->patch($account, $data);
        $array = $this->mapper->toArray($account);
        $this->assertEquals($data["Id"], $array["Id"]);
        $this->assertEquals($data["Name"], $array["Name"]);
    }
}
