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
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM ' . $this->name;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
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
        $pdo = Db::getConnection();
        $sql = <<<SQL
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = '$this->name' 
            AND COLUMN_NAME != 'id';"
        SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $pdo = Db::getConnection();

        $attributes = implode(', ', array_map(function ($attribute) {
            return '`' . $attribute . '`';
        }, array_keys($this->getColumnsByTableName()->getAttributeArray())));

        $sql = <<<SQL
            SELECT * FROM `$this->name`
            WHERE MATCH($attributes) AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE);
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
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
}
