<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: Dataset
 *
 * @see IModel
 */
class Dataset implements IModel
{
    /**
     * @var int|null $id
     */
    private ?int $id;
    /**
     * @var string|null $name
     */
    private ?string $name;
    /**
     * @var DatasetAttributes[] $attributes
     */
    private array $attributes;

    /**
     * @param int $id = null
     * @param string $name = null
     */
    public function __construct(int $id = null, string $name = null)
    {
        if (isset($id)) {
            $this->id = $id;
            $this->name = $name;
            $this->attributes = (new DatasetAttributes())->getAllObjectsByDatasetId($id);
        }
    }

    /**
     * getAllAsObjects
     *
     * @return Dataset[]
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM dataset';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new Dataset(...$object);
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
        $sql = 'DELETE FROM dataset WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return Dataset
     */
    public function getObjectById(int $id): Dataset
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM dataset WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = $result ? new Dataset(...$result) : null;

        return $return;
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        $pdo = Db::getConnection();
        $sql = 'UPDATE dataset SET name = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            [$this->name, $this->id]
        );
    }

    /**
     * insert
     *
     * @param string $name
     * @return Dataset
     */
    public function insert(string $name): Dataset
    {
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO dataset VALUES(NULL, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name]);
        $id = $pdo->lastInsertId();
        return new Dataset($id, $name);
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * getAttributes
     *
     * @return DatasetAttributes[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
