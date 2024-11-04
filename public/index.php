<?php

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

    /** Build Action Controller Name from $action */
    $controllerName = 'App\\Controllers\\' . ucfirst($action) . 'Controller';

    /**
     * Determine request method (POST or GET) and securely pass the corresponding
     * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
     */
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    /**
     * Invoke the controller and extract variables from the returned array
     */
    $controller = new $controllerName($data);
    $array = $controller->invoke();
    extract($array);

    /** After calling ShowFormController set the action depending on usecase - update or insert */
    $action = $controllerName === 'App\Controllers\ShowFormController' ? $controller->getAction() : $action;

    /** Get view set in the controller (Before catch, because it's possible that $controller is invalid) */
    $view = $controller->getView();
    $area = $controller->getArea();
} catch (PublicMessageException $exception) {
    $msg = $exception->getMessage();
    $view = $controller->getView();
} catch (Throwable $error) {
    $timestamp = (new DateTime())->format('Y-m-d H:i:s ');
    file_put_contents(LOG_PATH, $timestamp . $error->getMessage() . "\n", FILE_APPEND);
    $area = 'error';
    $view = 'message';
} finally {
    /** Include requested view */
    include __DIR__ . '/../src/Views/application.php';
}
