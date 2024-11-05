<?php

namespace App\Core;

/**
 * Class: ManageTable
 */
class ManageTable
{
    /**
     * @var string $name
     */
    private string $tableName;
    /**
     * @var array $attributes
     */
    private ?array $attributes;

    /**
     * @param string $name
     * @param array $attributes
     */
    public function __construct(string $name, array $attributes = null)
    {
        $this->tableName = $name;
        $this->attributes = $attributes;
    }

    /**
     * create
     *
     * @return void
     */
    public function create(): void
    {
        $pdo = Db::getConnection();
        // Iterate over attribute names, add default datatype VARCHAR(65535) to them.
        // Implode the resulting array to a comma seperated string.
        $attributeString = implode(', ', array_map(function ($attribute) {
            return '`' . $attribute . '`' . ' TEXT';
        }, $this->attributes));

        $sql = <<<SQL
            CREATE TABLE `$this->tableName` (
                id INT AUTO_INCREMENT PRIMARY KEY, 
                $attributeString
            );
        SQL;

        try {
            $pdo->exec($sql);
        } catch (\PDOException $e) {
            // Check if the error message indicates that the table already exists
            if ($e->getCode() === '42S01') { // SQLSTATE code for "table already exists"
                throw new PublicMessageException("Die Tabelle '$this->tableName' existiert bereits.");
            } else {
                // For other PDO exceptions
                throw new \Exception($e);
            }
        }
    }

    /**
     * alter
     *
     * @param string $oldName
     * @param array $oldAttributes
     * @return void
     */
    public function alter(string $oldName, array $oldAttributes): void
    {
        $pdo = Db::getConnection();
        $sql = [];
        $sql[] = 'ALTER TABLE ' . $oldName . ' RENAME TO ' . $this->tableName;
        foreach ($this->attributes as $index => $columnname) {
            if (isset($oldAttributes[$index])) {
                $oldAttribute = $oldAttributes[$index]->getAttributeName();
                $sql[] = <<<SQL
                    ALTER TABLE `$this->tableName` 
                    CHANGE `$oldAttribute` `$columnname` TEXT;
                SQL;
            } else {
                // If more attributes are given than existed before, a new column is added
                $sql[] = "ALTER TABLE `{$this->tableName}` ADD COLUMN `{$columnname}` TEXT;";
            }
        }

        foreach ($sql as $statement) {
            $pdo->exec($statement);
        }
    }

    /**
     * drop
     *
     * @return void
     */
    public function drop(): void
    {
        $pdo = Db::getConnection();
        $sql = "DROP TABLE `{$this->tableName}`;";
        $pdo->exec($sql);
    }

    /**
     * dropColumn
     *
     * @param string $attributeName
     * @return void
     */
    public function dropColumn(string $attributeName): void
    {
        $pdo = Db::getConnection();
        $sql = "ALTER TABLE `{$this->tableName}` DROP COLUMN `{$attributeName}`;";
        $pdo->exec($sql);
    }
}
