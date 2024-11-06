<?php

namespace App\Models;

/**
 * Class: Dataset
 *
 * @see IModel
 */
class Dataset extends BaseModel
{
    /**
     * @var string|null $name
     */
    private ?string $name;
    /**
     * @var DatasetAttribute[] $attributes
     */
    private array $attributes;

    /**
     * @param int $id = null
     * @param string $name = null
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * getAttributes
     *
     * @return DatasetAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * getAttributeNames
     *
     * @return array
     */
    public function getAttributeNames(): array
    {
        return array_map(fn ($attribute) => $attribute->getAttributeName(), $this->attributes);
    }

    protected function createObject(array $attributes): Dataset
    {
        return new Dataset(...$attributes);
    }
}
