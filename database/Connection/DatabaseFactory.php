<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/Connection/Database.php';

Class DatabaseFactory {

    //Singleton
    private static $connection;

    public static function getDatabase(){
        if(self::$connection == null){
           $ini = parse_ini_file('db.ini');

           $url = $ini['db_location'];
           $user = $ini['db_user'];
           $passw = $ini['db_passw'];
           $db = $ini['db_name'];
            self::$connection = new Database($url, $user, $passw, $db);
        }
        return self::$connection;
    }

}

?>
