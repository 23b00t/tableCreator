<?php

namespace App\Models;

use App\Core\Db;
use PDO;
use PDOStatement;

abstract class BaseModel
{
    /**
     * @var int|null $id
     */
    protected ?int $id;
    /**
     * @var string $tableName;
     */
    protected string $tableName;

    /**
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
        $this->tableName = $this->getTableName();
    }

    /**
     * getAllAsObjects
     *
     * @return object[]
     */
    public function getAllAsObjects(): array
    {
        $sql = "SELECT * FROM `$this->tableName`;";
        $stmt = $this->prepareAndExecuteQuery($sql);
        return $this->fetchAndCreateObjects($stmt);
    }

    /**
     * deleteObjectById
     *
     * @param int $id
     * @return void
     */
    public function deleteObjectById(int $id): void
    {
        $sql = "DELETE FROM `{$this->tableName}` WHERE id = ?;";
        $this->prepareAndExecuteQuery($sql, [$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return object|null
     */
    public function getObjectById(int $id): ?object
    {
        $sql = "SELECT * FROM `{$this->tableName}` WHERE id = ?;";
        $stmt = $this->prepareAndExecuteQuery($sql, [$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Retrun object or null if no object was found
        $return = $result ? $this->createObject($result) : null;

        return $return;
    }

    /**
     * insert
     *
     * @param array $values
     * @return object
     */
    public function insert(array $values): object
    {
        $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
        $sql = "INSERT INTO `{$this->tableName}` VALUES(NULL, {$placeholders});";
        $this->prepareAndExecuteQuery($sql, $values);
        $id = Db::getConnection()->lastInsertId();

        return $this->createObject(attributes: array_merge([$id], $values));
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
     * getTableName
     *
     * @return string
     */
    private function getTableName(): string
    {
        // Get the class name including namespace
        $caller = get_called_class();

        // Use `basename` on the class name using namespace separator
        return lcfirst(basename(str_replace('\\', '/', $caller)));
    }

    /**
     * prepareAndExecuteQuery
     *
     * @param string $sql
     * @param array $params, default []
     * @return PDOStatement
     */
    protected function prepareAndExecuteQuery(string $sql, array $params = []): PDOStatement
    {
        $pdo = Db::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * createObjects
     *
     * @param PDOStatement
     * @return object[]
     */
    protected function fetchAndCreateObjects(PDOStatement $stmt): array
    {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($attributeArray) {
            return $this->createObject($attributeArray);
        }, $results);
    }

    /**
     * createObject
     *
     * @param array $attributes
     * @return object
     */
    abstract protected function createObject(array $attributes): object;

    /**
     * update
     *
     * @return void
     */
    abstract public function update(): void;
}
