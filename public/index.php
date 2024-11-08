<?php

use App\Core\ControllerDispatcher;
use App\Core\ErrorHandler;
use App\Core\PublicMessageException;

try {
    include_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../vendor/autoload.php';

    /** @var string $area (Controller Name) */
    $area = $_REQUEST['area'] ?? 'dataset';

    /**
     * @var string $action (Controller action)
     * showTable as default action
     */
    $action = $_REQUEST['action'] ?? 'showTable';
    /** @var string $view [defaults to table] */
    $view = 'table';
    /**
     * @var string $msg
     * To be set in controllers. For custom success messages.
     */
    $msg = '';

    /**
     * Determine request method (POST or GET) and securely pass the corresponding
     * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
     */
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    /** $area, $action, $view and $msg are manipulated in the dispatcher as refferences */
    $dispatcher = new ControllerDispatcher($action, $data);
    /** The Dispatcher returns an array of object(s) received by the controller */
    $response = $dispatcher->dispatch();

    $view = $response->getView();
    $action = empty($response->getAction()) ? $action : $response->getAction();
    $area = empty($response->getArea()) ? $area : $response->getArea();
    $objectArray = $response->getObjectArray();
    $msg = $response->getMsg();

    extract($objectArray);

    ErrorHandler::validateViewPath($area, $view);
} catch (PublicMessageException $exception) {
    ErrorHandler::handlePublicMessageExceptions($exception, $dispatcher, $msg, $view);
} catch (Throwable $error) {
    ErrorHandler::handleThrowable($error, $area, $view);
} finally {
    include __DIR__ . '/../src/Views/application.php';
}
