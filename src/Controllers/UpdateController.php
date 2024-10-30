<?php

namespace App\Controllers;

use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class: UpdateController
 *
 * @see IController
 */
class UpdateController implements IController
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
     * @var array $postData
     */
    private array $postData;
    /**
     * @var string $view
     */
    private string $view;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        $this->area = $requestData['area'];
        $this->id = $requestData['id'];
        // Extract object attribute values from POST requestData
        $this->postData = (new FilterData($requestData))->filter();
        $this->view = 'table';
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'dataset') {
            $dataset = new Dataset(
                $this->id,
                $this->postData['datasetName'],
            );

            $dataset->update();

            // Iterate over POST attributes array that has the DatasetAttribute->id as key and its name as value
            foreach ($this->postData['attributes'] as $id => $name) {
                $datasetAttribute = new DatasetAttribute($id, $this->id, $name);
                $datasetAttribute->update();
            }

            $datasets = $dataset->getAllAsObjects();
            return [ 'datasets' => $datasets ];
        } elseif ($this->area === 'dynamicTable') {
            $tableRow = new TableRow($this->postData['tableName'], null, $this->id, $this->postData['attributes']);
            $tableRow->update();
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
