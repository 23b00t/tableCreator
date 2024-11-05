<?php

namespace App\Controllers;

use App\Models\Dataset;
use App\Models\TableRow;

/**
 * Class: ShowFormController
 *
 * @see BaseController
 */
class ShowFormController extends BaseController
{
    /**
     * @var int|null $id
     */
    private ?int $id;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        $this->id = $requestData['id'] ?? null;
        $this->action = 'insert';
        $this->view = 'form';
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
        /** Show empty form for insert */
        } else {
            if ($this->area === 'dynamicTable') {
                $tableRow = (new TableRow($this->tableName))->getColumnsByTableName();
                $array = [ 'tableRow' => $tableRow ];
            }
        }
        return $array;
    }
}
