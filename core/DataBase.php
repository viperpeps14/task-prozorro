<?php
class DataBase {
    protected static $_database;

    public static function Connect()
    {
        try{
            $dsn = "mysql:host=".DB_HOSTNAME.";dbname=".DB_DATABASE.";charset=utf8";
            self::$_database = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            self::$_database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //echo "Connection successful!<br>";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die;
        }
    }

    public static function handler() {
        return self::$_database;
    }
}
