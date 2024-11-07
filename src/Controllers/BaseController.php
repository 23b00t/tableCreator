<?php

namespace App\Controllers;

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
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dataset') {
            $this->datasetAction();
            $datasets = (new Dataset())->getAllAsObjects();

            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $this->tableRowAction($tableRow);
            $tableRows = $tableRow->getAllAsObjects();
            $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;

            return [ 'tableRows' => $tableRows ];
        }

        // Fallback (neede in DeleteController)
        return [];
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

    /**
     * getView
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * setView
     *
     * @param string $view
     * @return void
     */
    public function setView(string $view): void
    {
        $this->view = $view;
    }

    /**
     * getArea
     *
     * @return string
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * getAction
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * getMsg
     *
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }
}
