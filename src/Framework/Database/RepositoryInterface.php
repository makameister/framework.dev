<?php

namespace Framework\Database;

use PDO;

interface RepositoryInterface
{
    /**
     * Repository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo);

    public function count(): int;

    public function findAll(): array;

    public function findAllPaginated(): array;

    public function find(int $id);

    public function insert(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getTable(): string;

    public function setTable(string $table): void;

    public function getEntity(): string;

    public function setEntity(string $entity): void;
}
