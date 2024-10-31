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
    private array $attributes;

    /**
     * @param string $name
     * @param array $attributes
     */
    public function __construct(string $name, array $attributes)
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
        // $databaseAttributes = [];
        // foreach ($this->attributes as $attribute) {
        //     $databaseAttributes[] = $attribute . ' VARCHAR(255)';
        // }
        // $attributeString = implode(', ', $databaseAttributes);
        $attributeString = implode(', ', array_map(function ($attribute) {
            return $attribute . ' VARCHAR(255)';
        }, $this->attributes));

        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName . "` (id INT AUTO_INCREMENT PRIMARY KEY, " . $attributeString . ");";

        $pdo = Db::getConnection();
        $pdo->exec($sql);
    }
}
