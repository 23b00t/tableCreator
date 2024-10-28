<?php

namespace App\Models;

use App\Core\Db;
use PDO;

/**
 * Class: Main
 *
 * @see IModel
 */
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
     * @var MainAttributes[]
     */
    private array $attributes;

    /**
     * @param int $id = null
     * @param string $name = null
     */
    public function __construct(int $id = null, string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->attributes = (new MainAttributes())->getAllObjectsByMainId($id);
    }

    /**
     * getAllAsObjects
     *
     * @return Main[]
     */
    public function getAllAsObjects(): array
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM main';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        foreach ($results as $object) {
            $return[] = new Main(...$object);
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
        $sql = 'DELETE FROM main WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * getObjectById
     *
     * @param int $id
     * @return Main
     */
    public function getObjectById(int $id): Main
    {
        $pdo = Db::getConnection();
        $sql = 'SELECT * FROM main WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $return = $result ? new Main(...$result) : null;

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
        $sql = 'UPDATE main SET name = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            [$this->name, $this->id]
        );
    }

    /**
     * insert
     *
     * @param string $name
     * @return Main
     */
    public function insert(string $name): Main
    {
        $pdo = Db::getConnection();
        $sql = 'INSERT INTO main VALUES(NULL, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name]);
        $id = $pdo->lastInsertId();
        return new Main($id, $name);
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
     * @return MainAttributes[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
