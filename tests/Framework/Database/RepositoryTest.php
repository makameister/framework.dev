<?php

namespace Tests\Framework\Repository;

use Tests\Framework\Database\DatabaseTest;
use Tests\Resources\Repository\TestRepository;

class RepositoryTest extends DatabaseTest
{
    private $repository;

    protected function setUp(): void
    {
        $this->pdo = $this->getPDO();
        $this->repository = new TestRepository($this->pdo);
    }

    public function testCount()
    {
        $this->seedDatabase($this->pdo);
        $this->assertEquals(10, $this->repository->count());
    }

    public function testCountWithNoEntry()
    {
        $this->migrateDatabase($this->pdo);
        $this->assertEquals(0, $this->repository->count());
    }

    public function testFindAll()
    {
        $this->seedDatabase($this->pdo);
        $this->assertCount(10, $this->repository->findAll());
    }

    public function testFindAllWithNoEntry()
    {
        $this->migrateDatabase($this->pdo);
        $this->assertEquals([], $this->repository->findAll());
    }

    public function testFind()
    {
        $this->seedDatabase($this->pdo);
        $this->assertEquals('object', getType($this->repository->find(2)));
    }

    public function testFindNotFoundEntry()
    {
        $this->migrateDatabase($this->pdo);
        $this->assertNull($this->repository->find(1000));
    }

}