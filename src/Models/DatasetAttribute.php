<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: DatasetAttribute
 *
 * @see IModel
 */
class DatasetAttribute implements IModel
{
    /**
     * @var int|null $id
     */
    private ?int $id;
    /**
     * @var int|null $datasetId
     */
    private ?int $datasetId;
    /**
     * @var string|null $attributeName
     */
    private ?string $attributeName;

    /**
     * @param int $id = null
     * @param string $name = null
     */
    public function __construct(int $id = null, int $datasetId = null, string $attributeName = null)
    {
        if (isset($id)) {
            $this->id = $id;
            $this->datasetId = $datasetId;
            $this->attributeName = $attributeName;
        }
    }

    /**
     * getAllAsObjects
     *
     * @return DatasetAttribute[]
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM datasetAttribute';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new DatasetAttribute(...$object);
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
        $sql = 'DELETE FROM datasetAttribute WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return DatasetAttribute
     */
    public function getObjectById(int $id): DatasetAttribute
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM datasetAttribute WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = $result ? new DatasetAttribute(...$result) : null;

        return $return;
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        // INFO: No functionality for changing datasetId as it is not a valid use case
        $pdo = Db::getConnection();
        $sql = 'UPDATE datasetAttribute SET attributename = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            [$this->attributeName, $this->id]
        );
    }

    /**
     * insert
     *
     * @param int $datasetId
     * @param string $attributeName
     * @return DatasetAttribute
     */
    public function insert(int $datasetId, string $attributeName): DatasetAttribute
    {
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO datasetAttribute VALUES(NULL, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$datasetId, $attributeName]);
        $id = $pdo->lastInsertId();
        return new DatasetAttribute($id, $datasetId, $attributeName);
    }

    /**
     * getAllObjectsByDatasetId
     *
     * @param int $datasetId
     * @return DatasetAttribute[]
     */
    public function getAllObjectsByDatasetId(int $datasetId): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM datasetAttribute WHERE datasetId = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$datasetId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new DatasetAttribute(...$object);
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
     * getDatasetId
     *
     * @return int|null
     */
    public function getDatasetId(): ?int
    {
        return $this->datasetId;
    }

    /**
     * getAttributeName
     *
     * @return string|null
     */
    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }
}
