<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: Table
 * Dynamic table model
 *
 * @see IModel
 */
class Table implements IModel
{
    /**
     * @var array<int,mixed>
     */
    private array $attributes;

    /**
     * @param array<int,mixed> $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * getAllAsObjects
     *
     * @return Table[]
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM ' . $this->name;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new Table(...$object);
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
        $sql = 'DELETE FROM ' . $this->name . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return Table
     */
    public function getObjectById(int $id): Table
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM ' . $this->name . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = $result ? new Table(...$result) : null;

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
        }, $this->attributes));

        $pdo = Db::getConnection();
        $sql = 'UPDATE ' . $this->name . ' SET ' . $attributeString . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(...$this->attributeValues);
    }

    /**
     * insert
     *
     * @param array<int,mixed> $values
     * @return Table
     */
    public function insert(array $values): Table
    {
        // $placeholders = [];
        // foreach ($this->attributes as $_) {
        //     $placeholders[] = '?';
        // }
        // $pdo = Db::getConnection();
        // $sql = 'INSERT INTO ' . $this->name . ' VALUES(NULL, ' . implode(', ', $placeholders) . ')';
        $placeholders = rtrim(str_repeat('?, ', count($this->attributes)), ', ');
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO ' . $this->name . ' VALUES(NULL, ' . $placeholders . ')';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(...$values);
        $id = $pdo->lastInsertId();
        return new Table($id, ...$values);
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

    /**
     * getAttributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    /**
     * getAttributeValues
     *
     * @return array<int,mixed>
     */
    public function getAttributeValues(): array
    {
        return $this->attributeValues;
    }
}
