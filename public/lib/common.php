<?php

$BASE = dirname(__FILE__) . '/..';
require_once("$BASE/conf/db.php");

Class SafePDOFactory{

    public function __construct() {
        $this->dbh = false;
    }

    private static $factory;

    public static function getFactory() {
        if (!self::$factory) {
            self::$factory = new SafePDOFactory();
        }
        return self::$factory;
    }

    public static function exception_handler($exception) {
        // Output the exception details
        die('Uncaught exception: '. $exception->getMessage());
    }

    private $dbh;

    public function getConnection($dsn, $username='', $password='') {

        $dbh = $this->dbh;
        if (!$dbh) {
            // Temporarily change the PHP exception handler while building up connection
            set_exception_handler(array(__CLASS__, 'exception_handler'));
            $dbh = new PDO($dsn, $username, $password);
            // Change the exception handler back to whatever it was before
            restore_exception_handler();
        }

        $this->dbh = $dbh;
        return $dbh;
    }

}

class SafePDO {

    public static function connect() {
        $connection = SafePDOFactory::getFactory()->getConnection(mcConf::DSN, mcConf::USERNAME, mcConf::PASSWORD);
        return $connection;
    }

}
