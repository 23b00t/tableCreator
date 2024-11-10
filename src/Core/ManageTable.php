<?php

namespace App\Core;

/**
 * Class: ManageTable
 *
 * Manages database tables and their attributes (create, alter, drop operations).
 */
class ManageTable
{
    /**
     * @var string $tableName
     * Table name for the database operations
     */
    private string $tableName;

    /**
     * @var array|null $attributes
     * Table attributes (columns) that will be used in the table creation or alteration
     */
    private ?array $attributes;

    /**
     * @var PDO $pdo
     * PDO connection object to interact with the database
     */
    private \PDO $pdo;

    /**
     * __construct
     *
     * Initializes the table name, attributes, and PDO connection
     *
     * @param string $name
     * @param array|null $attributes
     */
    public function __construct(string $name, array $attributes = null)
    {
        $this->tableName = $name;
        $this->attributes = $attributes;
        $this->pdo = Db::getConnection();
    }

    /**
     * create
     *
     * Creates the table with the provided attributes in the database.
     * Each attribute gets a default datatype of TEXT.
     *
     * @return void
     */
    public function create(): void
    {
        // Iterate over attribute names, add default datatype TEXT to them.
        // Implode the resulting array to a comma separated string.
        $attributeString = implode(', ', array_map(fn ($attribute) => "`{$attribute}` TEXT", $this->attributes));

        // SQL query to create the table
        $sql = <<<SQL
            CREATE TABLE `$this->tableName` (
                id INT AUTO_INCREMENT PRIMARY KEY, 
                $attributeString
            );
        SQL;

        // Execute the SQL query
        $this->pdo->exec($sql);
    }

    /**
     * alter
     *
     * Alters an existing table: renames it, and adds/changes columns based on the new attributes.
     *
     * @param string $oldName
     * @param array $oldAttributes
     * @return void
     */
    public function alter(string $oldName, array $oldAttributes): void
    {
        // Rename the table
        $sql = ["ALTER TABLE `{$oldName}` RENAME TO `{$this->tableName}`;"];

        // Alter the table's columns
        foreach ($this->attributes as $index => $columnname) {
            if (isset($oldAttributes[$index])) {
                // If the attribute already exists, just rename it
                $oldAttribute = $oldAttributes[$index]->getAttributeName();
                $sql[] = "ALTER TABLE `{$this->tableName}` CHANGE `{$oldAttribute}` `{$columnname}` TEXT;";
            } else {
                // If there are new attributes, add them as columns
                $sql[] = "ALTER TABLE `{$this->tableName}` ADD COLUMN `{$columnname}` TEXT;";
            }
        }

        // Execute each SQL statement
        array_walk($sql, fn ($statement) => $this->pdo->exec($statement));
    }

    /**
     * drop
     *
     * Drops the table from the database
     *
     * @return void
     */
    public function drop(): void
    {
        $sql = "DROP TABLE `{$this->tableName}`;";
        $this->pdo->exec($sql);
    }

    /**
     * dropColumn
     *
     * Drops a column from the table
     *
     * @param string $attributeName
     * @return void
     */
    public function dropColumn(string $attributeName): void
    {
        $sql = "ALTER TABLE `{$this->tableName}` DROP COLUMN `{$attributeName}`;";
        $this->pdo->exec($sql);
    }
}
