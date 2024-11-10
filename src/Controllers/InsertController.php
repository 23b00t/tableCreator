<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class InsertController
 *
 * Controller for handling insertion logic of datasets, dataset attributes, and table rows.
 *
 * @see BaseController
 */
class InsertController extends BaseController
{
    /**
     * @var array $postData The data extracted from the POST request for insertion.
     */
    private array $postData;

    /**
     * Constructor
     *
     * Initializes the InsertController with request data and extracts the filtered POST data.
     *
     * @param array $requestData Data from the incoming request.
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        // Extract object attribute values from POST requestData
        $this->postData = (new FilterData($requestData))->filter();
        $this->msg = 'Erfolgreich hinzugefügt'; // Message indicating successful addition
    }

    /**
     * Handle the insertion of a dataset.
     *
     * Creates the dataset and its associated attributes, handling any missing attributes.
     *
     * @return void
     * @throws \InvalidArgumentException if attributes are missing.
     */
    protected function datasetAction(): void
    {
        // Early return with an exception if attributes are missing
        if (empty($this->postData['attributes'])) {
            throw new \InvalidArgumentException('missingColumns');
        }

        $datasetName = $this->postData['datasetName'];
        $attributes = array_values($this->postData['attributes']);

        // Create table and insert dataset into the database
        (new ManageTable($datasetName, $attributes))->create();
        $dataset = (new Dataset())->insert([$datasetName]);
        $id = $dataset->getId();

        // Insert dataset attributes into the database
        array_walk($attributes, fn ($attribute) => (new DatasetAttribute())->insert([$id, $attribute]));
    }

    /**
     * Handle the insertion of a table row.
     *
     * Inserts the provided attributes into the specified table.
     *
     * @param TableRow $tableRow The TableRow object to insert data into.
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        $tableRow->insert($this->postData['attributes']);
    }
}
