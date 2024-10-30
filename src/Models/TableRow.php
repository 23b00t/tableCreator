<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: TableRow
 * Dynamic table model
 *
 * @see IModel
 */
class TableRow implements IModel
{
    /**
     * @var string|null $name
     */
    private ?string $name;
    private ?array $attributeValues;
    private ?int $id;


    /**
     * @param string|null $name
     * @param array|null $attributes
     * @param array<int,mixed> $attributeValues
     */
    public function __construct(
        string $name = null,
        int $id = null,
        array $attributeValues = null
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->attributeValues = $attributeValues;
    }

    /**
     * getAllAsObjects
     *
     * @return TableRow[]
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM ' . $this->name;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $attributeValues) {
            $return[] = new TableRow(
                $this->name,
                (int)array_shift($attributeValues),
                $attributeValues
            );
        }

        return $return;
    }

    /**
     * deleteObjectById
     *
     * @param int $id
     * @return void
     */
    public function deleteObjectById(int $id): void
    {
        $pdo = Db::getConnection();
        $sql = "DELETE FROM " . $this->name . " WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return TableRow
     */
    public function getObjectById(int $id): TableRow
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM ' . $this->name . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $return = $result ? new TableRow($this->name, array_shift($result), $result) : null;

        return $return;
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        $attributeString = implode(', ', array_map(function ($attribute) {
            return $attribute . ' = ?';
        }, array_keys($this->attributeValues)));

        $pdo = Db::getConnection();
        $sql = 'UPDATE ' . $this->name . ' SET ' . $attributeString . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge(array_values($this->attributeValues), [$this->id]));
    }

    /**
     * insert
     *
     * @param array<int,mixed> $values
     * @return Table
     */
    public function insert(array $values): TableRow
    {
        $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO ' . $this->name . ' VALUES(NULL, ' . $placeholders . ')';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $id = $pdo->lastInsertId();

        return new TableRow($this->name, $id, $values);
    }

    /**
     * getColumsByTableName
     *
     * @return array
     */
    public function getColumsByTableName(): array
    {
        $pdo = Db::getConnection();
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $this->name . "';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getId
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getName
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAttributeValues(): ?array
    {
        return $this->attributeValues;
    }
}
