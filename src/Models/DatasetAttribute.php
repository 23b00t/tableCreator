<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: DatasetAttribute
 *
 * @see BaseModel
 */
class DatasetAttribute extends BaseModel
{
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
        parent::__construct($id);
        if (isset($id)) {
            $this->datasetId = $datasetId;
            $this->attributeName = $attributeName;
        }
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

    protected function createObject(array $attributes): DatasetAttribute
    {
        return new DatasetAttribute(...$attributes);
    }
}
