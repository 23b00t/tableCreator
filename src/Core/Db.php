<?php

namespace App\Core;

use DateTime;
use Exception;
use PDOException;
use PDO;

/**
 * Class Db
 * Establish Database Connection and return PDO-Object
 */

class Db
{
    /**
     * @var object $dbh
     */
    private static object $dbh;

    /**
     * getConnection
     *
     * @return object
     */
    public static function getConnection(): PDO
    {
        if (!isset(self::$dbh)) {
            // Connect to a MySQL database using driver invocation
            try {
                self::$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            } catch (PDOException $e) {
                $timestamp = (new DateTime())->format('Y-m-d H:i:s.u ');
                file_put_contents(LOG_PATH, $timestamp . $e->getMessage(), FILE_APPEND);
                throw new Exception("Es ist ein Fehler aufgetreten");
            }
        }
        return self::$dbh;
    }
}
