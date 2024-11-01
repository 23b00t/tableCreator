<?php

namespace App\Controllers;

use App\Models\TableRow;

class SearchController extends BaseController
{
    /**
     * @var string $searchTerm
     */
    private string $searchTerm;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
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
            // exception handling
            echo "Select a table";
        }
    }
}
