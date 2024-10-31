<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class: DeleteController
 *
 * @see BaseController
 */
class DeleteController extends BaseController
{
    /**
     * @var int $id
     */
    private int $id;
    /**
     * @var string|null $tableName
     */
    private ?string $tableName;


    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'];
        $this->tableName = $requestData['tableName'] ?? null;
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dataset') {
            // Instanciate object by id to hand over its name to ManageTable
            $dataset = (new Dataset())->getObjectById($this->id);
            // Delete it from index table
            $dataset->deleteObjectById($this->id);
            // Delete it from DB
            (new ManageTable($dataset->getName()))->drop();

            $datasets = $dataset->getAllAsObjects();
            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $tableRow->deleteObjectById($this->id);

            $tableRows = $tableRow->getAllAsObjects();

            return [ 'tableRows' => $tableRows ];
        }
    }
}
