<?php declare(strict_types=1);

namespace Framework\Database;

use PDO;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $entity;

    /**
     * Repository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function count(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM {$this->table};")->fetchColumn();
    }

    public function findAll(): array
    {
        return $this->pdo->query("SELECT * FROM {$this->table};")->fetchAll();
    }

    public function findAllPaginated(): array
    {
        return [];
    }

    public function find(int $id): ?Object
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->execute();
        return $statement->fetch() ?: null;
    }

    public function insert(array $data)
    {
    }

    public function update(int $id, array $data)
    {
    }

    public function delete(int $id)
    {
    }

    /**
     * @return mixed
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

}
