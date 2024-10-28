<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: Main
 *
 * @see IModel
 */
class MainAttributes implements IModel
{
    /**
     * @var int|null $id
     */
    private int $id;
    /**
     * @var int|null $mainId
     */
    private int $mainId;
    /**
     * @var string|null $attributeName
     */
    private string $attributeName;

    /**
     * @param int $id = null
     * @param string $name = null
     */
    public function __construct(int $id = null, int $mainId = null, string $attributeName = null)
    {
        $this->id = $id;
        $this->mainId = $mainId;
        $this->attributeName = $attributeName;
    }

    /**
     * getAllAsObjects
     *
     * @return array
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM mainAttributes';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new MainAttributes(...$object);
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
        $sql = 'DELETE FROM mainAttributes WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return MainAttributes
     */
    public function getObjectById(int $id): MainAttributes
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM mainAttributes WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = $result ? new MainAttributes(...$result) : null;

        return $return;
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        // INFO: No functionality for changeing mainId as it is not a valid use case
        $pdo = Db::getConnection();
        $sql = 'UPDATE mainAttributes SET attributename = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            [$this->attributeName, $this->id]
        );
    }

    /**
     * insert
     *
     * @param int $mainId
     * @param string $attributeName
     * @return MainAttributes
     */
    public function insert(int $mainId, string $attributeName): MainAttributes
    {
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO mainAttributes VALUES(NULL, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mainId, $attributeName]);
        $id = $pdo->lastInsertId();
        return new MainAttributes($id, $mainId, $attributeName);
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
     * getMainId
     *
     * @return int|null
     */
    public function getMainId(): ?int
    {
        return $this->mainId;
    }
}
