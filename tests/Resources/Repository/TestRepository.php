<?php

namespace Tests\Resources\Repository;

use Framework\Database\Repository;
use PDO;
use Tests\Resources\Entity\TestEntity;

class TestRepository extends Repository
{
    protected string $table = 'test';

    protected string $entity = TestEntity::class;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }
}
