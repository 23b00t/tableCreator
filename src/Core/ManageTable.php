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
     * @var PDO $pdo
     */
    private \PDO $pdo;

    /**
     * @param string $name
     * @param array $attributes
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
     * @return void
     */
    public function create(): void
    {
        // Iterate over attribute names, add default datatype VARCHAR(65535) to them.
        // Implode the resulting array to a comma seperated string.
        $attributeString = implode(', ', array_map(fn ($attribute) => "`{$attribute}` TEXT", $this->attributes));

        $sql = <<<SQL
            CREATE TABLE `$this->tableName` (
                id INT AUTO_INCREMENT PRIMARY KEY, 
                $attributeString
            );
        SQL;

        $this->pdo->exec($sql);
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
        $sql = ["ALTER TABLE `{$oldName}` RENAME TO `{$this->tableName}`;"];
        foreach ($this->attributes as $index => $columnname) {
            if (isset($oldAttributes[$index])) {
                $oldAttribute = $oldAttributes[$index]->getAttributeName();
                $sql[] = "ALTER TABLE `{$this->tableName}` CHANGE `{$oldAttribute}` `{$columnname}` TEXT;";
            } else {
                // If more attributes are given than existed before, a new column is added
                $sql[] = "ALTER TABLE `{$this->tableName}` ADD COLUMN `{$columnname}` TEXT;";
            }
        }

        array_walk($sql, fn ($statement) => $this->pdo->exec($statement));
    }

    /**
     * drop
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
     * @param string $attributeName
     * @return void
     */
    public function dropColumn(string $attributeName): void
    {
        $sql = "ALTER TABLE `{$this->tableName}` DROP COLUMN `{$attributeName}`;";
        $this->pdo->exec($sql);
    }
}
