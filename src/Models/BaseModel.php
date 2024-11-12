<?php

namespace App\Models;

use App\Core\Db;
use PDO;
use PDOStatement;

/**
 * Class: BaseModel
 *
 * @abstract
 */
abstract class BaseModel
{
    /**
     * @var int|null $id
     * The ID of the object, or null if not set
     */
    protected ?int $id;

    /**
     * @var string $tableName
     * The name of the table associated with the model
     */
    protected string $tableName;

    /**
     * @param int|null $id
     * Constructor for initializing the model with an optional ID
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
        $this->tableName = $this->getTableName();
    }

    /**
     * getAllAsObjects
     *
     * Retrieves all objects from the database as an array of objects
     *
     * @return object[] Array of objects representing rows in the table
     */
    public function getAllAsObjects(): array
    {
        $sql = "SELECT * FROM `$this->tableName`;";
        $stmt = $this->prepareAndExecuteQuery($sql);
        return $this->fetchAndCreateObjects($stmt);
    }

    /**
     * deleteObjectById
     *
     * Deletes an object from the database by its ID
     *
     * @param int $id The ID of the object to delete
     * @return void
     */
    public function deleteObjectById(int $id): void
    {
        $sql = "DELETE FROM `{$this->tableName}` WHERE id = ?;";
        $this->prepareAndExecuteQuery($sql, [$id]);
    }

    /**
     * getObjectById
     *
     * Retrieves a single object by its ID from the database
     *
     * @param int $id The ID of the object to retrieve
     * @return object|null The object or null if not found
     */
    public function getObjectById(int $id): ?object
    {
        $sql = "SELECT * FROM `{$this->tableName}` WHERE id = ?;";
        $stmt = $this->prepareAndExecuteQuery($sql, [$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return object or null if no object was found
        $return = $result ? $this->createObject($result) : null;

        return $return;
    }

    /**
     * insert
     *
     * Inserts a new object into the database
     *
     * @param array $values The values to insert into the table
     * @return object The created object with the inserted data
     */
    public function insert(array $values): object
    {
        $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');
        $sql = "INSERT INTO `{$this->tableName}` VALUES(NULL, {$placeholders});";
        $this->prepareAndExecuteQuery($sql, $values);
        $id = Db::getConnection()->lastInsertId();

        return $this->createObject(array_merge([$id], $values));
    }

    /**
     * getId
     *
     * Retrieves the ID of the object
     *
     * @return int|null The ID or null if not set
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getTableName
     *
     * Determines the table name based on the class name
     *
     * @return string The name of the table
     */
    private function getTableName(): string
    {
        // Get the class name including namespace
        $caller = get_called_class();

        // Use `basename` on the class name using namespace separator
        return lcfirst(basename(str_replace('\\', '/', $caller)));
    }

    /**
     * prepareAndExecuteQuery
     *
     * Prepares and executes a SQL query with parameters
     *
     * @param string $sql The SQL query to execute
     * @param array $params The parameters for the query (optional)
     * @return PDOStatement The prepared and executed PDO statement
     */
    protected function prepareAndExecuteQuery(string $sql, array $params = []): PDOStatement
    {
        $pdo = Db::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * fetchAndCreateObjects
     *
     * Fetches all results from the PDO statement and creates objects from them
     *
     * @param PDOStatement $stmt The PDO statement containing the results
     * @return object[] An array of created objects from the fetched results
     */
    protected function fetchAndCreateObjects(PDOStatement $stmt): array
    {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($attributeArray) {
            return $this->createObject($attributeArray);
        }, $results);
    }

    /**
     * createObject
     *
     * Abstract method to create an object from an array of attributes
     *
     * @param array $attributes The attributes to populate the object with
     * @return object The created object
     */
    abstract protected function createObject(array $attributes): object;

    /**
     * update
     *
     * Abstract method to update the object in the database
     *
     * @return void
     */
    abstract public function update(): void;
}
