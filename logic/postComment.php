<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data["success"] = false;
    $data["error"] = "Your input is invalid";
    if(UserDB::checkIfUserIDExists($_POST['userID']) && BlogpostDB::checkIfBlogpostIDExists($_POST['blogID'])) {
        if (!(!isset($_POST['title']) && !isset($_POST['text']))) {
            if (strlen($_POST['text']) < 2000 && strlen($_POST['text']) < 80) {
                $comment = new Comment(UserDB::getUserByToken($_COOKIE['Token'])->userID,$_POST['title'],$_POST['text'],date("Y-m-d"),$_POST['blogID']);
                CommentDB::addComment($comment);
                $data["success"] = true;
                $data["error"] = null;
            }
        }
    }
    else {
        $data["error"] = "It appears like something went wrong. Reload this page to see if the problem persists.";
    }
    echo json_encode($data);
}
else {
    header("location:../blog.php");
}

?>
