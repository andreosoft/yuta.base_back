<?php

namespace base\core;

use PDO;

class Db {

    public static function init_mysql() {
        $host = \A::$app->config['db_mysql']['host'];
        $db = \A::$app->config['db_mysql']['db'];
        $user = \A::$app->config['db_mysql']['user'];
        $pass = \A::$app->config['db_mysql']['password'];
        try {
            $dbco = new \PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
        $dbco->exec("set names utf8");
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        return $dbco;
    }

//    public static function init_mssql() {
//        $host = \A::$app->config['db_mssql']['host'];
//        $db = \A::$app->config['db_mssql']['db'];
//        $user = \A::$app->config['db_mssql']['user'];
//        $pass = \A::$app->config['db_mssql']['password'];
//        try {
//            $dbco = new \PDO("dblib:version=7.0;charset=UTF-8;host=$host;dbname=$db", "$user", "$pass");
//        } catch (PDOException $e) {
//            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
//            exit;
//        }
//        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//        return $dbco;
//    }

}
