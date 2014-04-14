<?php

require "base.php";
global $BASE;
require_once("$BASE/conf/db.php");

Class SafePDO extends PDO {
 
    public static function exception_handler($exception) {
        // Output the exception details
        die('Uncaught exception: '. $exception->getMessage());
    }

    public function __construct($dsn, $username='', $password='') {

        // Temporarily change the PHP exception handler while we . . .
        set_exception_handler(array(__CLASS__, 'exception_handler'));

        // . . . create a PDO object
        parent::__construct($dsn, $username, $password);

        // Change the exception handler back to whatever it was before
        restore_exception_handler();
    }

}

// Connect to the database with defined constants
$dbh = new SafePDO(mcConf::DSN, mcConf::USERNAME, mcConf::PASSWORD);
