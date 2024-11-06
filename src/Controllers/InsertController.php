<?php

namespace App\Controllers;

use App\Core\ErrorHandler;
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
        /**
         * INFO: Use a controller instance as a wrapper to allow the ErrorHandler to modify the view
         * in case of an exception.  By passing the controller itself, we can call instance-specific
         * methods like setView() from within the static ErrorHandler.
         */
        ErrorHandler::handleNoColumnsException($this);

        $datasetName = $this->postData['datasetName'];
        $attributes = array_values($this->postData['attributes']);

        try {
            (new ManageTable($datasetName, $attributes))->create();
            $dataset = (new Dataset())->insert([$datasetName]);
            $id = $dataset->getId();

            array_walk($attributes, fn ($attribute) => (new DatasetAttribute())->insert([$id, $attribute]));
        } catch (\PDOException $e) {
            ErrorHandler::handleDuplicateTableException($e, $this->postData['datasetName'], $this);
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
        $tableRow->insert($this->postData['attributes']);
    }
}
