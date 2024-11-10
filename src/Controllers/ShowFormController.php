<?php

namespace App\Controllers;

use App\Core\Response;
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
        $this->view = 'form';
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): Response
    {
        $objectArray = [];
        $this->action = isset($this->id) ? 'update' : 'insert';

        if ($this->area === 'dataset' && isset($this->id)) {
            $dataset = (new Dataset())->getObjectById($this->id);
            $objectArray = ['dataset' => $dataset];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = isset($this->id)
                ? (new TableRow($this->tableName))->getObjectById($this->id)
                : (new TableRow($this->tableName))->getColumnsByTableName();

            if (isset($this->id) && !$tableRow) {
                throw new \Exception('Ungültige id');
            }

            $objectArray = ['tableRow' => $tableRow];
        }

        $response = new Response($objectArray);
        $response->setView($this->view);
        $response->setAction($this->action);

        return $response;
    }
}
