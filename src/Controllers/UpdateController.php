<?php

namespace App\Controllers;

use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttributes;

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

            foreach ($this->postData['attributes'] as $id => $name) {
                $datasetAttribute = new DatasetAttributes($id, $this->id, $name);
                $datasetAttribute->update();
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
