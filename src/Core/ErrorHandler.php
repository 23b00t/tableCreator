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
     * @param string $view
     * Checks if the view file exists for the given area, throws an exception if not
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
     * Handles unexpected errors by logging the error message and setting a default error area and view
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
     * Maps specific exceptions to custom messages and views, and returns a response object
     */
    public static function handle(Throwable $e): Response
    {
        $response = new Response([]);
        $exceptionsMap = [
            '42S01' => [
                'msg' => "Achtung: Die Tabelle existiert bereits.",
                'view' => 'form'
            ],
            'missingColumns' => [
                'msg' => "Achtung: Bitte füge Spalten zu deiner Tabelle hinzu!",
                'view' => 'form'
            ],
            '42S21' => [
                'msg' => 'Achtung: Die Spalte existiert bereits:',
                'view' => 'form'
            ]
        ];

        /** Check if exception matches any predefined codes/messages and set appropriate response */
        foreach ($exceptionsMap as $key => $settings) {
            if (($e->getCode() === $key) || ($e->getMessage() === $key)) {
                $response->setMsg($settings['msg']);
                $response->setView($settings['view']);
                return $response;
            }
        }

        // If no predefined exception match, throw the original exception
        throw new Exception($e);
    }
}
