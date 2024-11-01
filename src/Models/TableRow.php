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
    public function __construct(
        string $name,
        int $id = null,
        array $attributeArray = null
    ) {
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
        $result = $this->query($sql);
        return $this->createObjects($result);
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
        }, array_keys($this->attributeArray)));

        $pdo = Db::getConnection();
        $sql = 'UPDATE ' . $this->name . ' SET ' . $attributeString . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge(array_values($this->attributeArray), [$this->id]));
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
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO ' . $this->name . ' VALUES(NULL, ' . $placeholders . ')';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $id = $pdo->lastInsertId();

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
        $result = $this->query($sql);
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
        // Create the comma-separated list for MATCH
        $matchAttributes = implode(', ', array_map(fn ($attribute) => "`$attribute`", $attributes));

        // SQL query
        $sql = <<<SQL
            SELECT * FROM `$this->name`
            WHERE MATCH($matchAttributes) AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE)
            OR $likeClause;
        SQL;

        // Execute the query and create objects
        $result = $this->query($sql);
        return $this->createObjects($result);
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
     * queryObjects
     *
     * @param string $sql
     * @return array
     */
    private function query(string $sql): array
    {
        $pdo = Db::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * createObjects
     *
     * @param array $queryResults
     * @return TableRow[]
     */
    private function createObjects(array $queryResults): array
    {
        $return = [];
        foreach ($queryResults as $attributeArray) {
            $return[] = new TableRow(
                $this->name,
                array_shift($attributeArray),
                $attributeArray
            );
        }

        return $return;
    }
}
