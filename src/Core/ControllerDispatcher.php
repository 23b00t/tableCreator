<?php

namespace App\Core;

class ControllerDispatcher
{
    /**
     * @var object|null $controller
     */
    private ?object $controller = null;
    /**
     * @var string $area
     */
    private string $area;
    /**
     * @var string $action
     */
    private string $action;
    /**
     * @var string $view
     */
    private string $view;
    /**
     * @var array $data
     */
    private array $data;
    /**
     * @var string $msg
     */
    private string $msg;


    /**
     * @param string &$area
     * @param string &$action
     * @param string &$view
     * @param array $data
     */
    public function __construct(string &$area, string &$action, string &$view, array $data, string &$msg)
    {
        $this->area = &$area;
        $this->action = &$action;
        $this->view = &$view;
        $this->data = $data;
        $this->msg = &$msg;
    }

    /**
     * dispatch
     *
     * @return array
     */
    public function dispatch(): array
    {
        /** Build Action Controller Name from $action */
        $controllerName = 'App\\Controllers\\' . ucfirst($this->action) . 'Controller';

        // Throw Exception if the Controller doesn't exist aka invalid $action
        if (!class_exists($controllerName)) {
            throw new \Exception("Controller $controllerName not found.");
        }

        /** Invoke the controller and save returned array of objects */
        $this->controller = new $controllerName($this->data);
        $result = $this->controller->invoke();

        $this->view = $this->controller->getView();
        $this->area = $this->controller->getArea();
        $this->action = $this->controller->getAction();
        $this->msg = $this->controller->getMsg();

        return $result;
    }

    /**
     * getView
     *
     * To be called in ErrorHandler to get the correct view even if it has failed to invoke the controller
     * @return string
     */
    public function getView(): string
    {
        return $this->controller ? $this->controller->getView() : 'table';
    }
}
