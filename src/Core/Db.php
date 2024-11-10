<?php

namespace App\Core;

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
     * Holds the PDO instance for database connection
     */
    private static object $dbh;

    /**
     * getConnection
     *
     * @return object
     * Returns the PDO instance, creating it if necessary
     */
    public static function getConnection(): PDO
    {
        if (!isset(self::$dbh)) {
            // Connect to a MySQL database using driver invocation
            try {
                self::$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            } catch (PDOException $e) {
                // Throws exception if there is an error while connecting
                throw new Exception($e);
            }
        }
        return self::$dbh;
    }
}
