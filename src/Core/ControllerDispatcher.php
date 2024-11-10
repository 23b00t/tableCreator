<?php

namespace App\Core;

class ControllerDispatcher
{
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

        /** Invoke the controller and return response object handed over by controller */
        return (new $controllerName($this->data))->invoke();
    }
}
