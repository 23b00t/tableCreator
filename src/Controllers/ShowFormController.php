<?php

namespace App\Controllers;

use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class: ShowFormController
 *
 * @see IController
 */
class ShowFormController implements IController
{
    /**
     * @var string $area
     */
    private string $area;
    /**
     * @var ?int $id
     */
    private ?int $id;
    /**
     * @var string $view
     */
    private string $view;
    /**
     * @var string $action
     */
    private string $action;
    private string $tableName;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        $this->area = $requestData['area'];
        $this->id = $requestData['id'] ?? null;
        $this->view = 'form';
        $this->action = 'insert';
        $this->tableName = $requestData['tableName'] ?? null;
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        $array = [];
        /** Show edit form with pre-filled fields */
        if (isset($this->id)) {
            $this->action = 'update';
            if ($this->area === 'dataset') {
                $dataset = (new Dataset())->getObjectById($this->id);
                $array = [  'dataset' => $dataset ];
            } elseif ($this->area = 'dynamicTable') {
                $tableRow = (new TableRow($this->tableName))->getObjectById($this->id);
                $array = [ 'tableRow' => $tableRow ];
            }
        } else {
            if ($this->area === 'dynamicTable') {
                $columns = (new TableRow($this->tableName))->getColumsByTableName();
                $array = [ 'columns' => $columns ];
            }
        }
        return $array;
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
     * getAction
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
