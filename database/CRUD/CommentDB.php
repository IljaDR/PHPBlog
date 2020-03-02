<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/blog/data/Comment.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/Connection/DatabaseFactory.php';

class CommentDB {

    private static function getConnection() {
        return DatabaseFactory::getDatabase();
    }

    public static function getCommentsByBlogpost($bp) {
        $result = self::getConnection()->executeQuery("SELECT * FROM Comment WHERE BlogpostID = '?'", array($bp->blogpostID));
        $resultArray = array();
        for($index = 0; $index < $result->num_rows; $index++){
            $row = $result->fetch_array();
            $new = self::convertRowToComment($row);
            $resultArray[$index] = $new;
        }
        return $resultArray;
    }

    public static function addComment($comment){
        return self::getConnection()->executeQuery("INSERT INTO Comment(UserID, BlogpostID, Title, Text, Date) VALUES ('?','?','?','?','?')",
            array($comment->userID, $comment->blogpostID, $comment->title, $comment->text, date("Y-m-d")));
    }

    public static function getCommentAmount($blog){
        return count(self::getCommentsByBlogpost($blog));
    }

    public static function convertRowToComment($row){
        return new Comment($row['UserID'],$row['Title'],$row['Text'],$row['Date'],$row['BlogpostID'],$row['CommentID']);
    }
}
?>