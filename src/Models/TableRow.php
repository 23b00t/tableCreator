<?php

namespace App\Models;

use PDO;

/**
 * Class TableRow
 *
 * Represents a dynamic table row model, which allows interaction with a table by its name and its attributes.
 *
 * @see BaseModel
 */
class TableRow extends BaseModel
{
    /**
     * @var array|null $attributeArray
     * The attributes of the table row, stored as an associative array where keys are column
     * names and values are column values.
     */
    private ?array $attributeArray;

    /**
     * Constructor for TableRow model
     *
     * Initializes a new TableRow object with a table name and optional attributes.
     *
     * @param string $name The name of the table this row belongs to
     * @param int|null $id The ID of the table row (optional)
     * @param array|null $attributeArray The attributes of the table row (optional)
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
     * Updates the current table row by setting the new values for its attributes.
     *
     * @return void
     */
    public function update(): void
    {
        // Prepare the SQL update statement
        $attributeString = implode(', ', array_map(function ($attribute) {
            return "`{$attribute}` = ?";
        }, array_keys($this->attributeArray)));

        // SQL query to update the row
        $sql = "UPDATE `{$this->tableName}` SET {$attributeString} WHERE id = ?;";
        $this->prepareAndExecuteQuery($sql, array_merge(array_values($this->attributeArray), [$this->id]));
    }

    /**
     * getColumnsByTableName
     *
     * Retrieves the columns of the table (excluding the 'id' column) and returns a TableRow object
     * initialized with the column names as attributes.
     *
     * @return TableRow The TableRow object initialized with column names
     */
    public function getColumnsByTableName(): TableRow
    {
        // SQL query to fetch column names from the database
        $sql = <<<SQL
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = ? 
            AND COLUMN_NAME != 'id';"
        SQL;
        $result = $this->prepareAndExecuteQuery($sql, [$this->tableName])->fetchAll(PDO::FETCH_ASSOC);

        // Fill attribute array with column names as keys and null as values
        $attributes = array_fill_keys(array_column($result, 'COLUMN_NAME'), null);

        return $this->createObject(['id' => null] + $attributes);
    }

    /**
     * getObjectsByFulltextSearch
     *
     * Retrieves TableRow objects that match the given search term by performing a full-text search
     * across all attributes (columns) of the table.
     *
     * @param string $searchTerm The search term to look for
     * @return TableRow[] An array of TableRow objects that match the search term
     */
    public function getObjectsByFulltextSearch(string $searchTerm): array
    {
        // Retrieve attribute names (columns) from the table
        $attributes = array_keys($this->getColumnsByTableName()->getAttributeArray());

        // Build a LIKE clause for each attribute to search for the term
        $likeClause = implode(' OR ', array_map(fn ($attribute) => "`$attribute` LIKE '%$searchTerm%'", $attributes));

        // SQL query for the full-text search
        $sql = "SELECT * FROM `{$this->tableName}` WHERE $likeClause;";

        // Execute the query and return the results as TableRow objects
        $result = $this->prepareAndExecuteQuery($sql);
        return $this->fetchAndCreateObjects($result);
    }

    /**
     * getName
     *
     * Returns the name of the table that this row represents.
     *
     * @return string The name of the table
     */
    public function getName(): string
    {
        return $this->tableName;
    }

    /**
     * getAttributeArray
     *
     * Returns the attributes of the table row as an associative array.
     *
     * @return array|null The attribute array, or null if not set
     */
    public function getAttributeArray(): ?array
    {
        return $this->attributeArray;
    }

    /**
     * createObject
     *
     * Creates a new TableRow object with the given attributes.
     *
     * @param array $attributes The attributes to initialize the TableRow object
     * @return TableRow The created TableRow object
     */
    protected function createObject(array $attributes): TableRow
    {
        // NOTE: Workaround for array_shift which is buggy with Numbers as key
        // (even if they are strings) that are not ment as indexes
        $id = $attributes[key($attributes)]; // Saves the first element
        unset($attributes[key($attributes)]); // removes the first element

        return new TableRow($this->tableName, $id, $attributes);
    }
}
