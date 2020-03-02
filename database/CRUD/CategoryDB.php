<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/blog/data/Category.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/Connection/DatabaseFactory.php';

class CategoryDB {

    private static function getConnection() {
        return DatabaseFactory::getDatabase();
    }

    public static function getCategories() {
        $result = self::getConnection()->executeQuery("SELECT * FROM Category");
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToCategory($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function getCategoryByID($id){
        $result = self::getConnection()->executeQuery("SELECT CategoryName FROM Category WHERE CategoryID = ?", array($id));
        return $result->fetch_array();
    }

    public static function addCategory($categoryName, $token){
        if(UserDB::checkIfAdmin($token))
        return self::getConnection()->executeQuery("INSERT INTO Category(CategoryName) VALUES ('?')", array($categoryName));
    }

    public static function convertRowToCategory($row){
        return new Category($row['CategoryName'],$row['CategoryID']);
    }
}

?>