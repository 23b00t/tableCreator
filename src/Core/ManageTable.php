<?php

namespace App\Core;

/**
 * Class: ManageTable
 *
 * @method create()
 * @method alter()
 * @method drop()
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
        // Iterate over attribute names, add default datatype VARCHAR(255) to them.
        // Implode the resulting array to a comma seperated string.
        $attributeString = implode(', ', array_map(function ($attribute) {
            return $attribute . ' VARCHAR(255)';
        }, $this->attributes));

        $sql = <<<SQL
                CREATE TABLE IF NOT EXISTS `$this->tableName` (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    $attributeString);
                SQL;

        $pdo->exec($sql);
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
        foreach ($this->attributes as $index => $column) {
            $oldAttribute = $oldAttributes[$index]->getAttributeName();
            $sql[] = <<<SQL
                     ALTER TABLE `$this->tableName` 
                     CHANGE `$oldAttribute` `$column` VARCHAR(255);
                     SQL;
        }
        // Modify datatype:
        // ALTER TABLE table_name MODIFY COLUMN column_name new_data_type;
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
        $sql = 'DROP TABLE ' . $this->tableName;
        $pdo->exec($sql);
    }
}
