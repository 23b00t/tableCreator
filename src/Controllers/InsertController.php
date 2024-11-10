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
        $this->msg = 'Erfolgreich hinzugefügt';
    }

    /**
     * datasetAction
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        // Early return with an exception if attributes are missing
        if (empty($this->postData['attributes'])) {
            throw new \InvalidArgumentException('missingColumns');
        }

        $datasetName = $this->postData['datasetName'];
        $attributes = array_values($this->postData['attributes']);

        (new ManageTable($datasetName, $attributes))->create();
        $dataset = (new Dataset())->insert([$datasetName]);
        $id = $dataset->getId();

        array_walk($attributes, fn ($attribute) => (new DatasetAttribute())->insert([$id, $attribute]));
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
