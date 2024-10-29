<?php

namespace App\Controllers;

use App\Models\Dataset;

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
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        $this->area = $requestData['area'];
        $this->id = $requestData['id'];
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
            $dataset->deleteObjectById($this->id);

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
