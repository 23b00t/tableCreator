<?php

namespace App\Models;

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
        $sql = 'UPDATE datasetAttribute SET attributename = ? WHERE id = ?';
        $this->prepareAndExecuteQuery($sql, [$this->attributeName, $this->id]);
    }

    /**
     * getAllObjectsByDatasetId
     *
     * @param int $datasetId
     * @return DatasetAttribute[]
     */
    public function getAllObjectsByDatasetId(int $datasetId): array
    {
        $sql = 'SELECT * FROM datasetAttribute WHERE datasetId = ?';

        $stmt = $this->prepareAndExecuteQuery($sql, [$datasetId]);
        return $this->fetchAndCreateObjects($stmt);
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
