<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/blog/data/User.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/Connection/DatabaseFactory.php';

class userDB {

    private static function getConnection() {
        return DatabaseFactory::getDatabase();
    }

    public static function checkCredentials($mail, $pass) {
        $hash = hash("SHA512",$pass);
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Email='?' AND Hash='?'", array($mail, $hash));
        return $result->num_rows == 1;
    }

    public static function createUser($mail, $pass, $username, $description = ""){
        $hash = hash("SHA512",$pass);
        $result = self::getConnection()->executeQuery("INSERT INTO Users(Username, Hash, Email, UserSince, Description) VALUES ('?','?','?','?','?')",
            array($username, $hash, $mail, date("Y-m-d"), $description));
        return $result;
    }

    public static function checkToken($token, $pass = null, $email = null){
        if($pass != null){
            $hash = hash("SHA512",$pass);
            $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Cookie='?' AND Hash='?' AND Email='?'",
                array($token, $hash, $email));
            return $result;
        }
        else {
            $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Cookie = '?'", array($token));
            return $result;
        }
    }

    public static function checkUsernameIsUsed($username){
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Username = '?'", array($username));
        return mysqli_fetch_row($result)[0];
    }

    public static function checkEmailIsUsed($mail){
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Email = '?'", array($mail));
        return mysqli_fetch_row($result)[0];
    }

    public static function generateToken(){
        $notUnique = true;
        //Makes sure no token will be generated that is already being used
        while($notUnique){
            //Line below from https://stackoverflow.com/questions/4356289/php-random-string-generator
            $token = substr(str_shuffle(MD5(microtime())), 0, 30);
            if(!self::checkToken($token))
                $notUnique = false;
        }
        return $token;
    }

    public static function setToken($pass, $mail){
        $hash = hash("SHA512",$pass);
        $result = self::getConnection()->executeQuery("UPDATE Users SET Token='?' WHERE Hash='?' AND Email = '?'",
            array(self::generateToken(),$hash, $mail));
        return $result;
    }

    public static function getToken($pass, $mail){
        $hash = hash("SHA512",$pass);
        $result = self::getConnection()->executeQuery("SELECT Token FROM Users WHERE Email='?' AND Hash='?'", array($mail, $hash));
        return mysqli_fetch_row($result)[0];
    }

    public static function getUserByToken($token){
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE Token = '?'", array($token));
        return self::convertRowToUser($result->fetch_array());
    }

    public static function checkIfAdmin($token){
        $result = self::getConnection()->executeQuery("SELECT Rights FROM Users WHERE Token = '?'", array($token));
        return $result->fetch_row()[0];
    }

    public static function getUserByID($id){
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE UserID = '?'", array($id));
        return self::convertRowToUser($result->fetch_array());
    }

    public static function checkIfUserIDExists($id){
        $result = self::getConnection()->executeQuery("SELECT * FROM Users WHERE UserID = '?'", array($id));
        return mysqli_fetch_row($result)[0];
    }

    public static function convertRowToUser($row){
        return new User($row['Username'], $row['Email'], $row['UserSince'], $row['Description'], $row['UserID']);
    }
}


?>
