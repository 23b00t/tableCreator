<?php

namespace App\Core;

class ControllerDispatcher
{
    /**
     * @var object|null $controller
     */
    private ?object $controller = null;
    /**
     * @var string $action
     */
    private string $action;
    /**
     * @var array $data
     */
    private array $data;


    /**
     * @param string &$area
     * @param string &$action
     * @param string &$view
     * @param array $data
     */
    public function __construct(string $action, array $data)
    {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * dispatch
     *
     * @return Response
     */
    public function dispatch(): Response
    {
        /** Build Action Controller Name from $action */
        $controllerName = 'App\\Controllers\\' . ucfirst($this->action) . 'Controller';

        // Throw Exception if the Controller doesn't exist aka invalid $action
        if (!class_exists($controllerName)) {
            throw new \Exception("Controller $controllerName not found.");
        }

        /** Invoke the controller and save returned array of objects */
        $this->controller = new $controllerName($this->data);
        return $this->controller->invoke();
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
