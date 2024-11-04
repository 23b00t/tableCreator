<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

class UpdateController extends BaseController
{
    /**
     * @var int $id
     */
    private int $id;
    /**
     * @var array $postData
     */
    private array $postData;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'];
        // Extract object attribute values from POST requestData
        $this->postData = (new FilterData($requestData))->filter();
    }

    /**
     * datasetAction
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        // Update child table
        (new ManageTable(
            $this->postData['datasetName'],
            array_values($this->postData['attributes'])
        ))->alter(...$this->getOldObject());

        // Update main table
        (new Dataset($this->id, $this->postData['datasetName']))->update();

        // Iterate over POST attributes array that has the DatasetAttribute->id as key and its name as value
        foreach ($this->postData['attributes'] as $id => $name) {
            (new DatasetAttribute($id, $this->id, $name))->update();
        }
    }

    /**
     * tableRowAction
     *
     * @param TableRow $tableRow
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        (new TableRow($this->postData['tableName'], $this->id, $this->postData['attributes']))->update();
    }

    /**
     * getOldObject
     *
     * @return array
     */
    private function getOldObject(): array
    {
        $oldDataset = (new Dataset())->getObjectById($this->id);
        $oldName = $oldDataset->getName();
        $oldAttributes = $oldDataset->getAttributes();
        return [$oldName, $oldAttributes];
    }
}
