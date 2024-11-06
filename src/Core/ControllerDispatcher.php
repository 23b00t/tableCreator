<?php

namespace App\Core;

class ControllerDispatcher
{
    private ?object $controller = null;

    /**
     * dispatch
     *
     * @param string $area
     * @param string $action
     * @param array $data
     * @return array
     */
    public function dispatch(string &$area, string &$action, string &$view, array $data): array
    {
        /** Build Action Controller Name from $action */
        $controllerName = 'App\\Controllers\\' . ucfirst($action) . 'Controller';

        // Throw Exception if the Controller doesn't exist aka invalid $action
        if (!class_exists($controllerName)) {
            throw new \Exception("Controller $controllerName not found.");
        }

        /** Invoke the controller and save returned array of objects */
        $this->controller = new $controllerName($data);
        $result = $this->controller->invoke();

        $view = $this->controller->getView();
        $area = $this->controller->getArea();
        $action = $this->controller->getAction();

        return $result;
    }

    /**
     * getView
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->controller ? $this->controller->getView() : 'table';
    }
}
