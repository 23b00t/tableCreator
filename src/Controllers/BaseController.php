<?php

namespace App\Controllers;

use App\Core\ErrorHandler;
use App\Core\Response;
use App\Models\TableRow;
use App\Models\Dataset;

abstract class BaseController
{
    /**
     * @var string $area
     */
    protected string $area;
    /**
     * @var string $view
     */
    protected string $view;
    /**
     * @var string|null $tableName
     */
    protected ?string $tableName;
    /**
     * @var string|null $action
     */
    protected ?string $action;
    /**
     * @var string $msg
     */
    protected string $msg;

    /**
     * __construct
     *
     * @param array $requestData
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
     * invoke
     *
     * @return Response|Throwable
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
     * datasetAction
     *
     * @return void
     */
    protected function datasetAction(): void
    {
    }

    /**
     * tableRowAction
     *
     * @param TableRow $tableRow
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
    }
}
