<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class ShowFormController
 *
 * Controller responsible for displaying the form for creating or updating a dataset or table row.
 *
 * @see BaseController
 */
class ShowFormController extends BaseController
{
    /**
     * @var int|null $id The ID of the dataset or table row to be updated, or null if creating a new entry.
     */
    private ?int $id;

    /**
     * Constructor
     *
     * Initializes the controller with request data and sets the form view.
     *
     * @param array $requestData The data from the incoming request.
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'] ?? null;
        $this->view = 'form'; // Default view is 'form'
    }

    /**
     * Invoke the action to display the form.
     *
     * Based on the ID and area, the controller either fetches an existing object to update or prepares for insertion.
     *
     * @return Response The response object containing the form data and view settings.
     */
    public function invoke(): Response
    {
        $objectArray = [];
        $this->action = isset($this->id) ? 'update' : 'insert'; // Set action based on the presence of ID

        // If the area is 'dataset' and ID is provided, fetch the dataset to update
        if ($this->area === 'dataset' && isset($this->id)) {
            $dataset = (new Dataset())->getObjectById($this->id);
            $objectArray = ['dataset' => $dataset];
        } elseif ($this->area === 'dynamicTable') {
            // If the area is 'dynamicTable', fetch table row by ID or columns if creating a new row
            $tableRow = isset($this->id)
                ? (new TableRow($this->tableName))->getObjectById($this->id)
                : (new TableRow($this->tableName))->getColumnsByTableName();

            // If ID is set but no table row is found, throw an exception
            if (isset($this->id) && !$tableRow) {
                throw new \Exception('Ungültige id');
            }

            $objectArray = ['tableRow' => $tableRow];
        }

        // Prepare response with object data, view, and action
        $response = new Response($objectArray);
        $response->setView($this->view);
        $response->setAction($this->action);

        return $response;
    }
}
