<?php

use App\Core\ControllerDispatcher;
use App\Core\ErrorHandler;

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

try {
    /** @var string $area (Controller name, defaults to 'dataset') */
    $area = $_REQUEST['area'] ?? 'dataset';
    /** @var string $action (Controller action, defaults to 'showTable') */
    $action = $_REQUEST['action'] ?? 'showTable';
    /** @var string $view (defaults to 'table') */
    $view = 'table';
    /** @var string $msg (message passed from the controller) */
    $msg = '';

    /**
     * Determine the request method (POST or GET) and securely pass the corresponding
     * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
     */
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    /** Find the correct controller and call it with the passed data */
    $dispatcher = new ControllerDispatcher($action, $data);
    /** The Dispatcher returns a Response object received by the controller */
    $response = $dispatcher->dispatch();

    /**
     * If the value is not set by the controller, it defaults to the pre-set value.
     * In this case, the value should not be changed.
     */
    $view = empty($response->getView()) ? $view : $response->getView();
    $action = empty($response->getAction()) ? $action : $response->getAction();
    $area = empty($response->getArea()) ? $area : $response->getArea();
    $msg = $response->getMsg();

    $objectArray = $response->getObjectArray();

    /** Extract the named object array */
    extract($objectArray);

    /** Catch manipulation of the URL by the user */
    ErrorHandler::validateViewPath($area, $view);
} catch (Throwable $error) {
    /** Handle unexpected errors and exceptions */
    ErrorHandler::handleThrowable($error, $area, $view);
} finally {
    /** Include the application view template */
    include __DIR__ . '/../src/Views/application.php';
}
