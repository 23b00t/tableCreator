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
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dataset') {
            $oldDataset = (new Dataset())->getObjectById($this->id);
            $oldName = $oldDataset->getName();
            $oldAttributes = $oldDataset->getAttributes();

            $dataset = new Dataset(
                $this->id,
                $this->postData['datasetName'],
            );

            $dataset->update();

            // Iterate over POST attributes array that has the DatasetAttribute->id as key and its name as value
            foreach ($this->postData['attributes'] as $id => $name) {
                $datasetAttribute = new DatasetAttribute($id, $this->id, $name);
                $datasetAttribute->update();
            }

            (new ManageTable(
                $this->postData['datasetName'],
                array_values($this->postData['attributes'])
            ))->alter($oldName, $oldAttributes);

            $datasets = $dataset->getAllAsObjects();
            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = new TableRow($this->postData['tableName'], $this->id, $this->postData['attributes']);
            $tableRow->update();
            $tableRows = $tableRow->getAllAsObjects();
            return [ 'tableRows' => $tableRows ];
        }
    }
}
