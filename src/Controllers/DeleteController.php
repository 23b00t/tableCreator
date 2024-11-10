<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Core\Response;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class DeleteController
 *
 * Controller for handling deletion logic of various entities such as datasets, dataset attributes, and table rows.
 *
 * @see BaseController
 */
class DeleteController extends BaseController
{
    /**
     * @var int $id The ID of the entity to be deleted.
     */
    private int $id;

    /**
     * Constructor
     *
     * Initializes the DeleteController with the request data and sets the message.
     *
     * @param array $requestData Data from the incoming request.
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'];
        $this->msg = 'Erfolgreich gelöscht'; // Message indicating successful deletion
    }

    /**
     * Invoke the delete action and return the appropriate Response.
     *
     * Handles the deletion process for different areas like dataset or datasetAttribute.
     *
     * @return Response The response object after deletion.
     */
    public function invoke(): Response
    {
        $response = parent::invoke();
        if ($this->area === 'datasetAttribute') {
            // Create DatasetAttribute object by ID
            $datasetAttribute = (new DatasetAttribute())->getObjectById($this->id);
            // Delete the dataset attribute from the DB and remove corresponding table row
            $this->datasetAttributeAction($datasetAttribute);
            // Create new Dataset object to update the view after deletion
            $dataset = (new Dataset())->getObjectById($datasetAttribute->getDatasetId());
            $objectArray = [ 'dataset' => $dataset ];
            $response->setObjectArray($objectArray);
            $response->setView('form');
            $response->setArea('dataset');
            $response->setAction('update');
        }

        return $response;
    }

    /**
     * Handle the deletion of a dataset.
     *
     * Deletes the dataset from the index table and from the database.
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        // Instantiate the dataset object by ID and delete it from the index table
        $dataset = (new Dataset())->getObjectById($this->id);
        $dataset->deleteObjectById($this->id);
        // Remove the dataset's corresponding table in the database
        (new ManageTable($dataset->getName()))->drop();
    }

    /**
     * Handle the deletion of a table row.
     *
     * Deletes the specified table row by its ID.
     *
     * @param TableRow $tableRow The TableRow object to be deleted.
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        $tableRow->deleteObjectById($this->id);
    }

    /**
     * Handle the deletion of a dataset attribute.
     *
     * Deletes the attribute from the child table, the index table, and updates the dataset edit form.
     *
     * @param DatasetAttribute $datasetAttribute The DatasetAttribute object to be deleted.
     * @return void
     */
    private function datasetAttributeAction(DatasetAttribute $datasetAttribute): void
    {
        // Delete the column corresponding to the dataset attribute from the child table
        (new ManageTable($this->tableName))->dropColumn($datasetAttribute->getAttributeName());
        // Delete the dataset attribute from the index table
        $datasetAttribute->deleteObjectById($this->id);
        // Attributes should be removed from the Dataset edit form for the next route
    }
}
