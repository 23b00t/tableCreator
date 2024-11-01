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
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'];
    }

    /**
     * datasetAction
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        // Instanciate object by id to hand over its name to ManageTable
        $dataset = (new Dataset())->getObjectById($this->id);
        // Delete it from index table
        $dataset->deleteObjectById($this->id);
        // Delete it from DB
        (new ManageTable($dataset->getName()))->drop();
    }

    /**
     * tableRowAction
     *
     * @param TableRow $tableRow
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        $tableRow->deleteObjectById($this->id);
    }
}
