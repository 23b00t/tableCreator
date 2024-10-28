<?php

namespace App\Controllers;

use App\Models\Main;

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
        $this->area = $requestData['area'] ?? 'main';
        $this->view = 'table';
    }

    /**
     * invoke
     *
     * @return array
     */
    public function invoke(): array
    {
        if ($this->area === 'main') {
            $mains = (new Main())->getAllAsObjects();
            return [ 'mains' => $mains ];
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
