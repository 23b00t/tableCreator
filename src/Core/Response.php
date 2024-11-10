<?php

namespace App\Core;

class Response
{
    /**
     * @var array $objectArray
     * Contains array of object(s)
     */
    private array $objectArray = [];

    /**
     * @var string $msg
     */
    private string $msg = '';

    /**
     * @var string $action
     */
    private string $action = '';

    /**
     * @var string $view
     */
    private string $view = '';

    /**
     * @var string $area
     */
    private string $area = '';

    /**
     * __construct
     *
     * @param array $objectArray
     */
    public function __construct(array $objectArray)
    {
        $this->objectArray = $objectArray;
    }

    /**
     * getObjectArray
     *
     * @return array
     */
    public function getObjectArray(): array
    {
        return $this->objectArray;
    }

    /**
     * getMsg
     *
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
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
     * setMsg
     *
     * @param string $msg
     * @return void
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * setAction
     *
     * @param string $action
     * @return void
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * setView
     *
     * @param string $view
     * @return void
     */
    public function setView(string $view): void
    {
        $this->view = $view;
    }

    /**
     * setObjectArray
     *
     * @param array $objectArray
     * @return void
     */
    public function setObjectArray(array $objectArray): void
    {
        $this->objectArray = $objectArray;
    }

    /**
     * getArea
     *
     * @return string
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * setArea
     *
     * @param string $area
     * @return void
     */
    public function setArea(string $area): void
    {
        $this->area = $area;
    }
}
