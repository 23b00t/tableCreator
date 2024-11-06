<?php

namespace App\Core;

class ControllerDispatcher
{
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
        $controller = new $controllerName($data);
        $result = $controller->invoke();

        $view = $controller->getView();
        /** Get area and action for the case they was manipulated by the controller */
        $area = $controller->getArea();
        $action = $controller->getAction();

        return $result;
    }
}
