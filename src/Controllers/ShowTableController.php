<?php

namespace App\Controllers;

use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class: ShowTableController
 *
 * @see BaseController
 */
class ShowTableController extends BaseController
{
    /**
     * @var string|null $tableName
     */
    private ?string $tableName;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
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
            $tableRows = empty($tableRows) ? [$tableRow->getColumnsByTableName()] : $tableRows;

            return [ 'tableRows' => $tableRows ];
        }
    }
}
