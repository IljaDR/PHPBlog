<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/blog/data/Blogpost.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/Connection/DatabaseFactory.php';

class BlogpostDB {

    private static function getConnection() {
        return DatabaseFactory::getDatabase();
    }

    public static function getBlogposts() {
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost ORDER BY BlogpostID DESC");
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function addBlogpost($blogpost){
        return self::getConnection()->executeQuery("INSERT INTO Blogpost(UserID, CategoryID, PicturePath, Date, Title, Text, Alt) VALUES ('?','?','?','?','?','?','?')",
            array($blogpost->userID, $blogpost->categoryID, $blogpost->picturePath, date("Y-m-d"), $blogpost->title, $blogpost->text, $blogpost->alt));
    }

    public static function getBlogpostsByCategory($category){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE CategoryID = '?'", array($category->categoryName));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function getBlogpostByUser($user){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE UserID = '?' ORDER BY BlogpostID DESC", array($user->userID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function getBlogpostByID($blogpostID){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE BlogpostID = '?'", array($blogpostID));
        return self::convertRowToBlogpost($result->fetch_array());
    }

    public static function removeBlogpost($blog, $token){
        if(UserDB::checkIfAdmin($token)){
            $result = self::getConnection()->executeQuery("DELETE FROM Blogpost WHERE BlogpostID = '?'", array($blog->blogpostID));
            return $result;
        }
        else
            return false;
    }

    public static function getArchiveArray($userID){
        $result = self::getConnection()->executeQuery("SELECT Date FROM Blogpost WHERE UserID = '?'", array($userID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $dateString = date('Y', strtotime($row['Date'])) . ", " . date('m', strtotime($row['Date']));
            $resultArray[$index] = $dateString;
        }
        return array_unique($resultArray);
    }

    public static function getBlogpostsFromArchive($archive, $userID){
        $year = substr($archive, 0, 4);
        $month = substr($archive, -1);
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE MONTH(Date) = ? AND YEAR(Date) = ? AND UserID = ?", array($month, $year, $userID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = $new;
        }
        return array_unique($resultArray);
    }

    public static function getMostPopularPosts(){
        $result = self::getConnection()->executeQuery("SELECT BlogpostID FROM Comment GROUP BY BlogpostID ORDER BY COUNT(*) DESC LIMIT 3");
        $resultArray = array();
        for($i = 0; $i < $result->num_rows; $i++){
            $row = $result->fetch_array();
            $resultArray[$i] = self::getBlogpostByID($row[0]);
        }
        return $resultArray;
    }

    public static function getMostPopularPostsFromUser($user){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE BlogpostID IN (SELECT BlogpostID FROM Comment GROUP BY BlogpostID ORDER BY COUNT(*) DESC) AND UserID = ? LIMIT 3",
            array($user->userID));
        $resultArray = array();
        for($i = 0; $i < $result->num_rows; $i++){
            $row = $result->fetch_array();
            $resultArray[$i] = self::getBlogpostByID($row[0]);
        }
        return $resultArray;
    }

    public static function getRandomPosts(){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE MONTH(Date) = ? ORDER BY RAND() DESC LIMIT 3", array(date('m')));
        $resultArray = array();
        for($i = 0; $i < $result->num_rows; $i++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$i] = $new;
        }
        return $resultArray;
    }

    public static function getCategoriesByUser($user){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE UserID = '?' ORDER BY BlogpostID DESC", array($user->userID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = CategoryDB::getCategoryByID($new->categoryID)['CategoryName'];
        }
        return array_unique($resultArray);
    }

    public static function getPostsByCategory($categoryID, $blogpostID){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE CategoryID = ? AND NOT BlogpostID = ?", array($categoryID, $blogpostID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToBlogpost($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function checkIfBlogpostIDExists($id){
        $result = self::getConnection()->executeQuery("SELECT * FROM Blogpost WHERE BlogpostID = '?'", array($id));
        return mysqli_fetch_row($result)[0];
    }

    public static function convertRowToBlogpost($row){
        return new Blogpost($row['UserID'],$row['CategoryID'],$row['PicturePath'],$row['Date'],$row['Title'],$row['Text'],$row['Alt'],$row['BlogpostID']);
    }
}

?>