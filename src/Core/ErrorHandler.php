<?php

namespace App\Core;

use DateTime;
use Exception;
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
     */
    public static function validateViewPath(string $area, string $view): void
    {
        /** Check if $area and $view are valid, otherwise throw Exception */
        $filePath = __DIR__ . '/../Views/' . $area . '/' . $view . '.php';
        !is_file($filePath) && throw new Exception("File not found: $filePath");
    }

    /**
     * handleThrowable
     *
     * @param Throwable $error
     * @param string &$area
     * @param string &$view
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

    /**
     * handle
     *
     * @param Throwable $e
     * @return Response
     */
    public static function handle(Throwable $e): Response
    {
        $response = new Response([]);
        if ($e->getCode() === '42S01') { // SQLSTATE code for "table already exists"
            $response->setMsg("Achtung: Die Tabelle existiert bereits.");
            $response->setView('form');
        } elseif ($e instanceof \InvalidArgumentException) {
            $response->setMsg("Achtung: Bitte füge Spalten zu deiner Tabelle hinzu!");
            $response->setView('form');
        } else {
            throw new Exception($e);
        }
        return $response;
    }
}
