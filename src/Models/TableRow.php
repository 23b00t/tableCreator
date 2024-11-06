<?php

namespace App\Models;

use PDO;

/**
 * Class: TableRow
 * Dynamic table model
 *
 * @see BaseModel
 */
class TableRow extends BaseModel
{
    /**
     * @var array|null $attributeArray
     */
    private ?array $attributeArray;


    /**
     * @param string $name
     * @param array $attributeArray
     */
    public function __construct(string $name, int $id = null, array $attributeArray = null)
    {
        parent::__construct($id);
        $this->attributeArray = $attributeArray;
        $this->tableName = $name;
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        $attributeString = implode(', ', array_map(function ($attribute) {
            return "`{$attribute}` = ?";
        }, array_keys($this->attributeArray)));

        $sql = "UPDATE `{$this->tableName}` SET {$attributeString} WHERE id = ?;";
        $this->prepareAndExecuteQuery($sql, array_merge(array_values($this->attributeArray), [$this->id]));
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
            WHERE TABLE_NAME = '$this->tableName' 
            AND COLUMN_NAME != 'id';"
        SQL;
        $result = $this->prepareAndExecuteQuery($sql)->fetchAll(PDO::FETCH_ASSOC);
        // array_column: get from $result all values with key COLUMN_NAME
        // array_fill_keys: take result from array_column as keys and fill the values with null
        $attributes = array_fill_keys(array_column($result, 'COLUMN_NAME'), null);

        return $this->createObject(array_merge([null], $attributes));
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
        $sql = "SELECT * FROM `{$this->tableName}` WHERE $likeClause;";

        // Execute the query and create objects
        $result = $this->prepareAndExecuteQuery($sql);
        return $this->fetchAndCreateObjects($result);
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->tableName;
    }

    /**
     * getAttributeArray
     *
     * @return array|null
     */
    public function getAttributeArray(): ?array
    {
        return $this->attributeArray;
    }

    protected function createObject(array $attributes): TableRow
    {
        return new TableRow($this->tableName, array_shift($attributes), $attributes);
    }
}
