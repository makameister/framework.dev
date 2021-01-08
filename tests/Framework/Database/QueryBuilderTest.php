<?php

namespace Tests\Framework\Database;

use Framework\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $query;

    protected function setUp(): void
    {
        $this->query = new QueryBuilder();
    }

    public function testSimpleSelect()
    {
        $this->assertEquals(
            "SELECT * FROM table",
            $this->query
                ->select()
                ->from("table")
                ->__toString()
        );
    }

    public function testSelectWithFields()
    {
        $this->assertEquals(
            "SELECT column1, column2 FROM table",
            $this->query
                ->select('column1', 'column2')
                ->from("table")
                ->__toString()
        );
    }

    public function testSelectWithCondition()
    {
        $this->assertEquals(
            "SELECT column1, column2 FROM table WHERE id = :id AND name != :name",
            $this->query
                ->select('column1', 'column2')
                ->from("table")
                ->where("id = :id", "name != :name")
                ->__toString()
        );
    }

    public function testSelectWithInnerJoins()
    {
        $this->assertEquals(
            "SELECT t1.column1, t2.column2 FROM table1 t1 INNER JOIN table2 t2 ON t1.id = t2.id",
            $this->query
                ->select('t1.column1', 't2.column2')
                ->from("table1 t1")
                ->join("table2 t2", "t1.id = t2.id")
                ->__toString()
        );
    }

    public function testOrderBy()
    {
        $this->assertEquals(
            "SELECT t1.column1, t1.column2, t2.column1 FROM table1 t1 " .
                "INNER JOIN table2 t2 ON t1.id = t2.id ORDER BY t1.column1, t1.column2 ASC, t2.column2 DESC",
            $this->query
                ->select('t1.column1', 't1.column2', 't2.column1')
                ->from("table1 t1")
                ->join("table2 t2", "t1.id = t2.id")
                ->orderBy("ASC", "t1.column1", "t1.column2")
                ->orderBy("DESC", "t2.column2")
                ->__toString()
        );
    }

    public function testLimit()
    {
        $this->assertEquals(
            "SELECT * FROM table LIMIT 10 OFFSET 0",
            $this->query
                ->select()
                ->from("table")
                ->limit(10)
                ->__toString()
        );
    }

    public function testLimitWithOffset()
    {
        $this->assertEquals(
            "SELECT * FROM table LIMIT 20 OFFSET 10",
            $this->query
                ->select()
                ->from("table")
                ->limit(20, 10)
                ->__toString()
        );
    }
}
