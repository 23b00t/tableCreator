<?php

namespace App\Core;

use DateTime;

class ErrorHandler
{
    /**
     * validateViewPath
     *
     * @param string $area
     * @param stirng $view
     * @return ErrorHandler
     */
    public static function validateViewPath(string $area, string $view): void
    {
        /** Check if $area and $view are valid, otherwise throw Exception */
        $filePath = __DIR__ . '/../Views/' . $area . '/' . $view . '.php';
        !is_file($filePath) && throw new \Exception("File not found: $filePath");
    }

    /**
     * handlePublicMessageExceptions
     *
     * @return array
     */
    public static function handlePublicMessageExceptions(\Throwable $exception, ControllerDispatcher $dispatcher): array
    {
        /** Catch custom exceptions to display the message to the user, e.g. if the user trys to make a duplicate table */
        $msg = $exception->getMessage();
        $view = $dispatcher->getView();

        return [ 'msg' => $msg, 'view' => $view ];
    }

    /**
     * handleThrowable
     *
     * @param \Throwable $error
     * @return array
     */
    public static function handleThrowable(\Throwable $error): array
    {
        /** Catch all other unpredictable errors and exceptions */
        $timestamp = (new DateTime())->format('Y-m-d H:i:s ');
        file_put_contents(LOG_PATH, $timestamp . $error->getMessage() . "\n", FILE_APPEND);
        /** manually set $area and $view as controllers may not have worked in error case */
        return [ 'area' => 'error', 'view' => 'message' ];
    }
}
