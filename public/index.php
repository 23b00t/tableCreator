<?php

use App\Core\ControllerDispatcher;
use App\Core\ErrorHandler;
use App\Core\PublicMessageException;

try {
    include_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../vendor/autoload.php';

    /**
     * @var string $area (Controller Name)
     */
    $area = $_REQUEST['area'] ?? 'dataset';

    /**
     * @var string $action (Controller action)
     * showTable as default action
     */
    $action = $_REQUEST['action'] ?? 'showTable';
    /**
     * @var string $view [defaults to table]
     */
    $view = 'table';

    /**
     * Determine request method (POST or GET) and securely pass the corresponding
     * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
     */
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    /** $area, $action and $view are manipulated in the dispatcher as refferences */
    $dispatcher = new ControllerDispatcher();
    /** The Dispatcher returns an array of object(s) received by the controller */
    $array = $dispatcher->dispatch($area, $action, $view, $data);
    extract($array);

    ErrorHandler::validateViewPath($area, $view);
} catch (PublicMessageException $exception) {
    extract(ErrorHandler::handlePublicMessageExceptions($exception, $dispatcher));
} catch (Throwable $error) {
    extract(ErrorHandler::handleThrowable($error));
} finally {
    include __DIR__ . '/../src/Views/application.php';
}
