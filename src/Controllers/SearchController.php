<?php

namespace App\Controllers;

use App\Models\TableRow;

class SearchController
{
    private $searchTerm;
    private $area;

    private string $view;
    private $tableName;


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
        $this->searchTerm = $requestData['searchTerm'];
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $tableRows = $tableRow->getObjectsByFulltextSearch($this->searchTerm);
            $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;

            return [ 'tableRows' => $tableRows ];
        } else {
            echo "Select a table";
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
