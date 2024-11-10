<?php

namespace App\Models;

/**
 * Class DatasetAttribute
 *
 * Represents an attribute of a dataset, providing methods for interacting with dataset attributes.
 *
 * @see BaseModel
 */
class DatasetAttribute extends BaseModel
{
    /**
     * @var int|null $datasetId
     * The ID of the dataset to which this attribute belongs
     */
    private ?int $datasetId;

    /**
     * @var string|null $attributeName
     * The name of the attribute
     */
    private ?string $attributeName;

    /**
     * Constructor for DatasetAttribute model
     *
     * Initializes a new DatasetAttribute object with optional ID, dataset ID, and attribute name.
     *
     * @param int|null $id The ID of the dataset attribute (optional)
     * @param int|null $datasetId The ID of the dataset this attribute belongs to (optional)
     * @param string|null $attributeName The name of the attribute (optional)
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
     * Updates the dataset attribute by modifying its attribute name.
     *
     * Note: datasetId cannot be changed as it is a fixed relation to a specific dataset.
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
     * Retrieves all DatasetAttribute objects associated with a given dataset ID.
     *
     * @param int $datasetId The ID of the dataset to fetch attributes for
     * @return DatasetAttribute[] Array of DatasetAttribute objects
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
     * Retrieves the dataset ID that this attribute belongs to.
     *
     * @return int|null The ID of the dataset, or null if not set
     */
    public function getDatasetId(): ?int
    {
        return $this->datasetId;
    }

    /**
     * getAttributeName
     *
     * Retrieves the name of the attribute.
     *
     * @return string|null The name of the attribute, or null if not set
     */
    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    /**
     * createObject
     *
     * Creates a new DatasetAttribute object from the given attributes.
     *
     * @param array $attributes The attributes to initialize the DatasetAttribute object
     * @return DatasetAttribute The created DatasetAttribute object
     */
    protected function createObject(array $attributes): DatasetAttribute
    {
        return new DatasetAttribute(...$attributes);
    }
}
