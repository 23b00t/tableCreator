<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class: InsertController
 *
 * @see BaseController
 */
class InsertController extends BaseController
{
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
        $dataset = new Dataset();
        $datasetObj = $dataset->insert(
            $this->postData['datasetName'],
        );

        $id = $datasetObj->getId();

        foreach ($this->postData['attributes'] as $attribute) {
            (new DatasetAttribute())->insert($id, $attribute);
        }

        (new ManageTable($this->postData['datasetName'], array_values($this->postData['attributes'])))->create();
    }

    /**
     * tableRowAction
     *
     * @param TableRow $tableRow
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        $tableRow->insert($this->postData['attributes']);
    }
}
