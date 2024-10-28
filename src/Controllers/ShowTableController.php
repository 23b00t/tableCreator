<?php

namespace App\Controllers;

use App\Models\Dataset;

/**
 * Class: ShowTableController
 *
 */
class ShowTableController implements IController
{
    /**
     * @var string $area
     */
    private string $area;
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
        $this->area = $requestData['area'] ?? 'dataset';
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
            $datasets = (new Dataset())->getAllAsObjects();
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
