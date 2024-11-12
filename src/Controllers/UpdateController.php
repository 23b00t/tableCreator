<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class: UpdateController
 *
 * Controller for updating data
 *
 * @see BaseController
 */
class UpdateController extends BaseController
{
    /**
     * @var int $id The unique identifier for the dataset or table row being updated.
     */
    private int $id;

    /**
     * @var array $postData The data extracted from the POST request to update the dataset or table row.
     */
    private array $postData;

    /**
     * __construct
     *
     * Initializes the UpdateController with request data and sets the message for a successful update.
     *
     * @param array $requestData The data from the request.
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'];
        // Extract object attribute values from POST requestData
        $this->postData = (new FilterData($requestData))->filter();
        $this->msg = 'Erfolgreich aktualisiert';
    }

    /**
     * datasetAction
     *
     * Updates the dataset, its table structure, and attributes based on the provided data.
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        // If table doesn't contain columns, set its attributes to an empty array
        $values = isset($this->postData['attributes']) ? $this->postData['attributes'] : [];

        // Update the child table
        (new ManageTable($this->postData['datasetName'], array_values($values)))
            ->alter(...$this->getOldObject());

        // Update the main dataset table
        (new Dataset($this->id, $this->postData['datasetName']))->update();

        // Iterate over the POST attributes array, which contains the DatasetAttribute->id
        // as the key and its name as the value
        foreach ($values as $id => $name) {
            $dAttr = new DatasetAttribute($id, $this->id, $name);
            // If object doesn't exist yet (i.e., a new column was added), create it
            $dAttr->getObjectById($id) ? $dAttr->update() : $dAttr->insert([$this->id, $name]);
        }
    }

    /**
     * tableRowAction
     *
     * Updates the table row based on the provided attributes.
     *
     * @param TableRow $tableRow The table row object to be updated.
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        // Update the table row with the provided attributes
        (new TableRow($this->postData['tableName'], $this->id, $this->postData['attributes']))->update();
    }

    /**
     * getOldObject
     *
     * Retrieves the old values of the dataset to be used for updating the corresponding DB table and rows.
     *
     * @return array An array containing the dataset name and its attributes.
     */
    private function getOldObject(): array
    {
        // ManageTable needs the old values to find the corresponding DB table and rows
        $oldDs = (new Dataset())->getObjectById($this->id);
        return [$oldDs->getName(), $oldDs->getAttributes()];
    }
}
