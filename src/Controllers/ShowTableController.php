<?php

namespace App\Controllers;

use App\Models\Dataset;
use App\Models\TableRow;

/*
 * Class: ShowTableController
 *
 * @see IController
 */
class ShowTableController implements IController
{
    /**
     * @var string $area
     */
    private string $area;
    /**
     * @var string $view
     */
    private string $view;
    private ?string $tableName;

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
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dataset') {
            $datasets = (new Dataset())->getAllAsObjects();
            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $tableRows = $tableRow->getAllAsObjects();

            return [ 'tableRows' => $tableRows ];
        }
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
}
