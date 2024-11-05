<?php

namespace App\Models;

use App\Core\Db;
use PDO;
use PDOStatement;

/**
 * Class: TableRow
 * Dynamic table model
 *
 * @see IModel
 */
class TableRow implements IModel
{
    /**
     * @var string $name
     */
    private string $name;
    /**
     * @var int|null $id
     */
    private ?int $id;
    /**
     * @var array|null $attributeArray
     */
    private ?array $attributeArray;


    /**
     * @param string $name
     * @param int $id
     * @param array $attributeArray
     */
    public function __construct(string $name, int $id = null, array $attributeArray = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->attributeArray = $attributeArray;
    }

    /**
     * getAllAsObjects
     *
     * @return TableRow[]
     */
    public function getAllAsObjects(): array
    {
        $sql = 'SELECT * FROM ' . $this->name;
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
        $sql = "DELETE FROM " . $this->name . " WHERE id = ?";
        $this->prepareAndExecuteQuery($sql, [$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return TableRow
     */
    public function getObjectById(int $id): TableRow
    {
        $sql = 'SELECT * FROM ' . $this->name . ' WHERE id = ?';
        $stmt = $this->prepareAndExecuteQuery($sql, [$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Retrun object or null if no object was found
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
        }, array_keys($this->attributeArray)));

        $sql = 'UPDATE ' . $this->name . ' SET ' . $attributeString . ' WHERE id = ?';
        $this->prepareAndExecuteQuery($sql, array_merge(array_values($this->attributeArray), [$this->id]));
    }

    /**
     * insert
     *
     * @param array $values
     * @return TableRow
     */
    public function insert(array $values): TableRow
    {
        $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
        $sql = 'INSERT INTO ' . $this->name . ' VALUES(NULL, ' . $placeholders . ')';
        $this->prepareAndExecuteQuery($sql, $values);
        $id = Db::getConnection()->lastInsertId();

        return new TableRow($this->name, $id, $values);
    }

    /**
     * getColumnsByTableName
     *
     * @return TableRow
     */
    public function getColumnsByTableName(): TableRow
    {
        $sql = <<<SQL
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = '$this->name' 
            AND COLUMN_NAME != 'id';"
        SQL;
        $result = $this->prepareAndExecuteQuery($sql)->fetchAll(PDO::FETCH_ASSOC);
        // array_column: get from $result all values with key COLUMN_NAME
        // array_fill_keys: take result from array_column as keys and fill the values with null
        $attributes = array_fill_keys(array_column($result, 'COLUMN_NAME'), null);

        return new TableRow($this->name, null, $attributes);
    }

    /**
     * getObjectsByFulltextSearch
     *
     * @param string $searchTerm
     * @return TableRow[]
     */
    public function getObjectsByFulltextSearch(string $searchTerm): array
    {
        // Prepare query
        // Retrieve the attribute names from the table
        $attributes = array_keys($this->getColumnsByTableName()->getAttributeArray());
        // Join LIKE statements for each attribute using array_map and implode
        $likeClause = implode(' OR ', array_map(fn ($attribute) => "`$attribute` LIKE '%$searchTerm%'", $attributes));

        // SQL query
        $sql = "SELECT * FROM `{$this->name}` WHERE $likeClause;";

        // Execute the query and create objects
        $result = $this->prepareAndExecuteQuery($sql);
        return $this->fetchAndCreateObjects($result);
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributeArray(): ?array
    {
        return $this->attributeArray;
    }

    /**
     * prepareAndExecuteQuery
     *
     * @param string $sql
     * @param array $params, default []
     * @return PDOStatement
     */
    private function prepareAndExecuteQuery(string $sql, array $params = []): PDOStatement
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
     * @return TableRow[]
     */
    private function fetchAndCreateObjects(PDOStatement $stmt): array
    {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $attributeArray) {
            $return[] = new TableRow(
                $this->name,
                array_shift($attributeArray),
                $attributeArray
            );
        }

        return $return;
    }
}
