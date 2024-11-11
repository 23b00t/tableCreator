<?php

namespace App\Controllers;

use App\Core\ErrorHandler;
use App\Core\Response;
use App\Models\TableRow;
use App\Models\Dataset;

/**
 * Class: BaseController
 *
 * @abstract
 */
abstract class BaseController
{
    /**
     * @var string $area The entity on which the logic is applied (e.g., 'dataset' or 'dynamicTable').
     */
    protected string $area;

    /**
     * @var string $view The view to be rendered.
     */
    protected string $view;

    /**
     * @var string|null $tableName The name of the table for dynamic table actions.
     */
    protected ?string $tableName;

    /**
     * @var string|null $action The action to be executed by the controller.
     */
    protected ?string $action;

    /**
     * @var string $msg Message to be passed along with the response.
     */
    protected string $msg;

    /**
     * Constructor
     *
     * Initializes the controller with request data.
     *
     * @param array $requestData Data from the incoming request.
     */
    public function __construct(array $requestData)
    {
        $this->area = $requestData['area'] ?? 'dataset';
        $this->view = 'table';
        $this->tableName = $requestData['tableName'] ?? null;
        $this->action = $requestData['action'] ?? null;
        $this->msg = '';
    }

    /**
     * Invoke the controller action and return a Response object.
     *
     * Handles the business logic for the given area (dataset or dynamicTable).
     *
     * @return Response The response object.
     */
    public function invoke(): Response
    {
        try {
            $objectArray = [];
            if ($this->area === 'dataset') {
                $this->datasetAction();
                $datasets = (new Dataset())->getAllAsObjects();
                $objectArray = ['datasets' => $datasets];
            } elseif ($this->area === 'dynamicTable') {
                $tableRow = (new TableRow($this->tableName));
                $this->tableRowAction($tableRow);
                $tableRows = $tableRow->getAllAsObjects();

                $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;
                $objectArray = ['tableRows' => $tableRows];
            }

            $response = new Response($objectArray);
            $response->setMsg($this->msg);
            $response->setView($this->view);
            return $response;
        } catch (\Throwable $e) {
            return ErrorHandler::handle($e);
        }
    }

    /**
     * Handle actions related to the dataset area.
     *
     * This method should be overridden in a derived class to define dataset-specific logic.
     *
     * @return void
     */
    protected function datasetAction(): void
    {
    }

    /**
     * Handle actions related to a specific table row.
     *
     * This method should be overridden in a derived class to define table row-specific logic.
     *
     * @param TableRow $tableRow The TableRow object for the dynamic table.
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
    }
}
