<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class: DeleteController
 *
 * @see IController
 */
class DeleteController implements IController
{
    /**
     * @var string $area
     */
    private string $area;
    /**
     * @var int $id
     */
    private int $id;
    /**
     * @var string $view
     */
    private string $view;
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
        $this->area = $requestData['area'];
        $this->id = $requestData['id'];
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
            // Instanciate object by id to hand over its name to ManageTable
            $dataset = (new Dataset())->getObjectById($this->id);
            // Delete it from index table
            $dataset->deleteObjectById($this->id);
            // Delete it from DB
            (new ManageTable($dataset->getName()))->drop();

            $datasets = $dataset->getAllAsObjects();
            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = (new TableRow($this->tableName));
            $tableRow->deleteObjectById($this->id);

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
