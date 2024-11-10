<?php

namespace App\Models;

/**
 * Class Dataset
 *
 * Represents a dataset and provides methods for interacting with it
 *
 * @see IModel
 */
class Dataset extends BaseModel
{
    /**
     * @var string|null $name
     * The name of the dataset
     */
    private ?string $name;

    /**
     * @var DatasetAttribute[] $attributes
     * The attributes associated with the dataset
     */
    private array $attributes;

    /**
     * Constructor for Dataset model
     *
     * Initializes a new Dataset object with optional ID and name.
     * Also loads the attributes associated with the dataset if ID is provided.
     *
     * @param int|null $id The ID of the dataset (optional)
     * @param string|null $name The name of the dataset (optional)
     */
    public function __construct(int $id = null, string $name = null)
    {
        parent::__construct($id);
        if (isset($id)) {
            $this->name = $name;
            $this->attributes = (new DatasetAttribute())->getAllObjectsByDatasetId($id);
        }
    }

    /**
     * update
     *
     * Updates the dataset in the database by modifying its name
     *
     * @return void
     */
    public function update(): void
    {
        $sql = 'UPDATE dataset SET name = ? WHERE id = ?';
        $this->prepareAndExecuteQuery($sql, [$this->name, $this->id]);
    }

    /**
     * getName
     *
     * Retrieves the name of the dataset
     *
     * @return string|null The name of the dataset, or null if not set
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * getAttributes
     *
     * Retrieves the attributes associated with the dataset
     *
     * @return DatasetAttribute[] Array of DatasetAttribute objects
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * getAttributeNames
     *
     * Retrieves the names of all attributes associated with the dataset
     *
     * @return array An array of attribute names
     */
    public function getAttributeNames(): array
    {
        return array_map(fn ($attribute) => $attribute->getAttributeName(), $this->attributes);
    }

    /**
     * createObject
     *
     * Creates a new Dataset object from the given attributes
     *
     * @param array $attributes The attributes to initialize the Dataset object
     * @return Dataset The created Dataset object
     */
    protected function createObject(array $attributes): Dataset
    {
        return new Dataset(...$attributes);
    }
}
