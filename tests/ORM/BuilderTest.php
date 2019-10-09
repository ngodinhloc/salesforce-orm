<?php

namespace SalesforceTest\ORM;

use PHPUnit\Framework\TestCase;
use Salesforce\ORM\Query\Builder;

class BuilderTest extends TestCase
{
    /** @var Builder */
    protected $builder;

    public function setUp()
    {
        parent::setUp();
        $this->builder = new Builder();
    }

    public function testFrom()
    {
        $from = 'Account';
        $this->builder->from($from);
        $query = $this->builder->getQuery();
        $this->assertContains("FROM " . $from, $query);
    }

    public function testSelect()
    {
        $select = ["id", "name"];
        $this->builder->select($select);
        $query = $this->builder->getQuery();
        $this->assertContains(implode(',', $select), $query);
    }

    public function testWhere()
    {
        $col = "Id";
        $value = "12345";
        $where = ["{$col}={$value}"];
        $this->builder->where($where);
        $query = $this->builder->getQuery();
        $this->assertContains("{$col} = '{$value}'", $query);
    }

    public function testAndWhere()
    {
        $col = "Id";
        $value = "12345";
        $where = ["{$col}={$value}"];
        $this->builder->where($where);
        $this->builder->AndWhere("Id = 12345");
        $query = $this->builder->getQuery();
        $this->assertContains("{$col} = '{$value}'", $query);
    }

    public function testOrWhere()
    {
        $col = "Id";
        $value = "12345";
        $where = ["{$col}={$value}"];
        $this->builder->where($where);
        $this->builder->OrWhere("Id = 12345");
        $query = $this->builder->getQuery();
        $this->assertContains("{$col} = '{$value}'", $query);
    }

    public function testOrder()
    {
        $col = "Id";
        $value = "12345";
        $where = ["{$col}={$value}"];
        $this->builder->where($where);
        $this->builder->order(["ASC", "DESC"]);
        $query = $this->builder->getQuery();
        $this->assertContains("{$col} = '{$value}'", $query);
    }

    public function testLimit()
    {
        $limit = 10;
        $this->builder->limit($limit);
        $query = $this->builder->getQuery();
        $this->assertContains("LIMIT " . $limit, $query);
    }
}
