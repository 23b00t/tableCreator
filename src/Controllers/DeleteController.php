<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
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

    public function invoke(): array
    {
        $result = parent::invoke();
        if ($this->area === 'datasetAttribute') {
            // Create DatasetAttribute object
            $datasetAttribute = (new DatasetAttribute())->getObjectById($this->id);
            // Delete it from DB and delete its corresponding row in the table it refferes to
            $this->datasetAttributeAction($datasetAttribute);
            // Create new Dataset object and set area and view to redisplay the edit form without the deleted attribute
            $dataset = (new Dataset())->getObjectById($datasetAttribute->getDatasetId());
            $this->area = 'dataset';
            $this->view = 'form';
            $result = [ 'dataset' => $dataset ];
        }
        return $result;
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

    /**
     * datasetAttributeAction
     *
     * @param DatasetAttribute $datasetAttribute
     * @return void
     */
    private function datasetAttributeAction(DatasetAttribute $datasetAttribute): void
    {
        (new ManageTable($this->tableName))->dropColumn($datasetAttribute->getAttributeName());
        $datasetAttribute->deleteObjectById($this->id);
    }
}
