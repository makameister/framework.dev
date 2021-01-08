<?php declare(strict_types=1);

namespace Framework\Database;

class QueryBuilder
{

    /**
     * @var array<string>
     */
    private array $fields;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var array<string>
     */
    private array $where = [];

    /**
     * @var array<string>
     */
    private array $joins = [];

    /**
     * @var array<string>
     */
    private array $order = [];

    /**
     * @var string|null
     */
    private ?string $limit = null;

    /**
     * @param string ...$fields
     * @return $this
     */
    public function select(string ...$fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string[] $conditions
     * @return $this
     */
    public function where(string ...$conditions): self
    {
        $this->where = array_merge($this->where, $conditions);
        return $this;
    }

    /**
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return $this
     */
    public function join(string $table, string $condition, string $type = 'INNER'): self
    {
        $this->joins[] = [$type . " JOIN", $table, "ON " . $condition];
        return $this;
    }

    /**
     * @param string $direction
     * @param string ...$fields
     * @return $this
     */
    public function orderBy(string $direction, string ...$fields): self
    {
        $this->order = array_merge($this->order, [implode(", ", $fields) . " $direction"]);
        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, ?int $offset = 0): self
    {
        $this->limit = "LIMIT $limit OFFSET $offset";
        return $this;
    }

    public function __toString()
    {
        $parts = ["SELECT"];
        if ($this->fields) {
            $parts[] = implode(", ", $this->fields);
        } else {
            $parts[] = "*";
        }
        $parts[] = "FROM $this->table";
        if ($this->joins) {
            $parts[] = implode(" ", array_map(function ($join) {
                return implode(" ", $join);
            }, $this->joins));
        }
        if ($this->where) {
            $parts[] = "WHERE";
            $parts[] = implode(" AND ", $this->where);
        }
        if ($this->order) {
            $parts[] = "ORDER BY";
            $parts[] = implode(", ", $this->order);
        }
        if ($this->limit) {
            $parts[] = $this->limit;
        }

        return implode(" ", $parts);
    }
}
