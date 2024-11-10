<?php

namespace App\Core;

class ControllerDispatcher
{
    /**
     * @var string $action The action to be executed, corresponding to the controller.
     */
    private string $action;

    /**
     * @var array $data The data passed to the controller for processing.
     */
    private array $data;

    /**
     * __construct
     *
     * Initializes the dispatcher with the action to be executed and the data to be passed to the controller.
     *
     * @param string $action The action to be dispatched.
     * @param array $data The data associated with the action.
     */
    public function __construct(string $action, array $data)
    {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * dispatch
     *
     * Dispatches the action to the corresponding controller, invokes it, and returns the response object.
     *
     * @return Response The response object returned by the controller.
     * @throws \Exception If the controller class does not exist for the given action.
     */
    public function dispatch(): Response
    {
        // Build the fully qualified controller class name based on the action
        $controllerName = 'App\\Controllers\\' . ucfirst($this->action) . 'Controller';

        // Throw an exception if the controller class does not exist (invalid action)
        if (!class_exists($controllerName)) {
            throw new \Exception("Controller $controllerName not found.");
        }

        // Instantiate the controller and invoke it, returning the response object
        return (new $controllerName($this->data))->invoke();
    }
}
