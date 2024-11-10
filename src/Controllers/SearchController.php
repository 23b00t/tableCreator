<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\TableRow;

/**
 * Class SearchController
 *
 * Controller for handling search functionality on table rows based on a search term.
 *
 * @see BaseController
 */
class SearchController extends BaseController
{
    /**
     * @var string $searchTerm The term used to search table rows.
     */
    private string $searchTerm;

    /**
     * Constructor
     *
     * Initializes the SearchController with the request data and sets the search term.
     *
     * @param array $requestData Data from the incoming request, including the search term.
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->searchTerm = $requestData['searchTerm'];
    }

    /**
     * Invoke the search action and return the search results.
     *
     * Searches the table rows for the provided search term and returns the matching results.
     *
     * @return Response The response object containing the search results.
     */
    public function invoke(): Response
    {
        // Create TableRow object and search for matching rows using full-text search
        $tableRow = (new TableRow($this->tableName));
        $tableRows = $tableRow->getObjectsByFulltextSearch($this->searchTerm);
        // If no results are found, return the column headers
        $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;

        return (new Response([ 'tableRows' => $tableRows ]));
    }
}
