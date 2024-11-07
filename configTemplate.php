<?php

// Define the version of the application for reference
const VERSION = 1.0;

// Database Data Source Name (DSN) specifies the connection details for the MySQL/MariaDB database
const DB_DSN = 'mysql:dbname=tableCreator;host=127.0.0.1';

// Database username for authentication
const DB_USER = 'YOUR_USER_NAME';

// Database password for authentication
const DB_PASSWD = 'YOUR_PASSWORD';

// Path to the log file for recording application logs and error messages
const LOG_PATH = 'your/log/path/tableCreator_log.txt';

/*
 * IMPORTANT:
 * The user associated with DB_USER must have sufficient privileges to:
 * - CREATE TABLE: for creating new tables
 * - INSERT: for adding data to tables
 * - ALTER: for modifying table structure (e.g., renaming columns)
 * - DROP TABLE: for deleting tables
 * - DELETE: for removing records from tables
 * Without these privileges, certain actions in the application will fail.
 */
