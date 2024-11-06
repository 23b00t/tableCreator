<?php

use App\Core\ControllerDispatcher;
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
    $view = 'table'; // default view

    /**
     * Determine request method (POST or GET) and securely pass the corresponding
     * data ($_POST or $_GET) to the controller, ensuring proper handling of input.
     */
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    $array = (new ControllerDispatcher())->dispatch($area, $action, $view, $data);

    extract($array);

    /** Check if $area and $view are valid, otherwise throw Exception */
    $filePath = __DIR__ . '/../src/Views/' . $area . '/' . $view . '.php';
    !is_file($filePath) && throw new \Exception("File not found: $filePath");
} catch (PublicMessageException $exception) {
    /** Catch custom exceptions to display the message to the user, e.g. if the user trys to make a duplicate table */
    $msg = $exception->getMessage();
    $view = $controller->getView();
} catch (Throwable $error) {
    /** Catch all other unpredictable errors and exceptions */
    $timestamp = (new DateTime())->format('Y-m-d H:i:s ');
    file_put_contents(LOG_PATH, $timestamp . $error->getMessage() . "\n", FILE_APPEND);
    /** manually set $area and $view as controllers may not have worked in error case */
    $area = 'error';
    $view = 'message';
} finally {
    /** Include requested view */
    include __DIR__ . '/../src/Views/application.php';
}
