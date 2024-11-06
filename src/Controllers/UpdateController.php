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
        // If table doesn't contain columns set its attributes to []
        $values = isset($this->postData['attributes']) ? $this->postData['attributes'] : [];
        // Update child table
        (new ManageTable(
            $this->postData['datasetName'],
            array_values($values)
        ))->alter(...$this->getOldObject());

        // Update main table
        (new Dataset($this->id, $this->postData['datasetName']))->update();

        // Iterate over POST attributes array that has the DatasetAttribute->id as key and its name as value
        foreach ($values as $id => $name) {
            $datasetAttribute = new DatasetAttribute($id, $this->id, $name);
            if ($datasetAttribute->getObjectById($id)) {
                $datasetAttribute->update();
            } else {
                // If object doesn't exist yet, i.e. a new column was added
                $datasetAttribute->insert([$this->id, $name]);
            }
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
        // ManageTable needs the old values to find the corresponding DB table and rows
        $oldDataset = (new Dataset())->getObjectById($this->id);
        $oldName = $oldDataset->getName();
        $oldAttributes = $oldDataset->getAttributes();
        return [$oldName, $oldAttributes];
    }
}
