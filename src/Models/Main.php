<?php

namespace App\Models;

class Main implements IModel
{
    /**
     * @var int|null $id
     */
    private int $id;
    /**
     * @var string|null $name
     */
    private string $name;

    /**
     * @param int $id = null
     * @param string $name = null
     */
    public function __construct(int $id = null, string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getAllAsObjects(): array
    {
    }

    public function deleteObjectById(int $id): void
    {
    }

    public function getObjectById(int $id): Main
    {
    }

    public function update(): void
    {
    }
}
