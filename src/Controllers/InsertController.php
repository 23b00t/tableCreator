<?php

namespace App\Controllers;

use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttributes;

/**
 * Class: InsertController
 *
 * @see IController
 */
class InsertController implements IController
{
    /**
     * @var string $area
     */
    private string $area;
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
            $dataset = new Dataset();
            $datasetObj = $dataset->insert(
                $this->postData['datasetName'],
            );

            $id = $datasetObj->getId();

            foreach ($this->postData['attributes'] as $attribute) {
                (new DatasetAttributes())->insert($id, $attribute);
            }

            $datasets = $dataset->getAllAsObjects();
            return [ 'datasets' => $datasets ];
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
