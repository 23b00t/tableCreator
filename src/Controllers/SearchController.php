<?php

namespace App\Controllers;

use App\Core\Response;
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
     * @return Response
     */
    public function invoke(): Response|\Exception
    {
        if ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $tableRows = $tableRow->getObjectsByFulltextSearch($this->searchTerm);
            $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;

            return (new Response([ 'tableRows' => $tableRows ]));
        } else {
            throw new \Exception('SeachError: no valid table selected!');
        }
    }
}
