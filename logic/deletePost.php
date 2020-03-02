<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $check = UserDB::checkIfAdmin($_COOKIE['Token']);
    if($check){
        BlogpostDB::removeBlogpost(BlogpostDB::getBlogpostByID($_GET['id']), $_COOKIE['Token']);
        header("location:../admin.php");
    }
    else{
        header("location:index.php");
    }
}
else{
header("location:index.php");
}

?>