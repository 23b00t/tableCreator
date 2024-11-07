<?php

namespace App\Core;

use DateTime;
use Exception;
use PDOException;
use Throwable;

/**
 * Class: ErrorHandler
 *
 * Custom static error handler
 */
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
        !is_file($filePath) && throw new Exception("File not found: $filePath");
    }

    /**
     * handlePublicMessageExceptions
     *
     * @param Throwable $exception
     * @param ControllerDispatcher $dispatcher
     * @param string &$message
     * @param string &$view
     * @return array
     */
    public static function handlePublicMessageExceptions(
        Throwable $exception,
        ControllerDispatcher $dispatcher,
        string &$msg = null,
        string &$view
    ): void {
        /** Catch custom exceptions to display the message to the user, e.g. if the user trys to make a duplicate table */
        $msg = $exception->getMessage();
        $view = $dispatcher->getView();
    }

    /**
     * handleDuplicateTableException
     *
     * @param PDOException $e
     * @param string $name
     * @param object $controller
     * @return void
     */
    public static function handleDuplicateTableException(PDOException $e, string $name, object $controller): void
    {
        if ($e->getCode() === '42S01') { // SQLSTATE code for "table already exists"
            $controller->setView('form');
            throw new PublicMessageException("Die Tabelle '{$name}' existiert bereits.");
        } else {
            throw new Exception($e);
        }
    }

    /**
     * handleNoColumnsException
     *
     * @param object $controller
     * @param array $attributes
     * @return void
     */
    public static function handleNoColumnsException(object $controller, array $attributes = null): void
    {
        if (!isset($attributes)) {
            $controller->setView('form');
            throw new PublicMessageException('Bitte füge Spalten zu deiner Tabelle hinzu!');
        }
    }

    /**
     * handleThrowable
     *
     * @param \Throwable $error
     * @param string &$area
     * @param string &$view
     * @return array
     */
    public static function handleThrowable(Throwable $error, string &$area, string &$view): void
    {
        /** Catch all other unpredictable errors and exceptions */
        $timestamp = (new DateTime())->format('Y-m-d H:i:s ');
        file_put_contents(LOG_PATH, $timestamp . $error->getMessage() . "\n", FILE_APPEND);

        /** manually set $area and $view as controllers may not have worked in error case */
        $area = 'error';
        $view = 'message';
    }
}