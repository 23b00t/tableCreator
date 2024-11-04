<?php

namespace App\Models;

/**
 * IModel
 * Interface for basic methods of Models
 * @method insert
 */
interface IModel
{
    /**
     * getAllAsObjects
     *
     * @return array
     */
    public function getAllAsObjects(): array;

    /**
     * deleteObjectById
     *
     * @param int $id
     * @return void
     */
    public function deleteObjectById(int $id): void;

    /**
     * getObjectById
     *
     * @param int $id
     * @return Object|null
     */
    public function getObjectById(int $id): ?object;

    /**
     * update
     *
     * @return void
     */
    public function update(): void;
}
