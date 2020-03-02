<?php
//Code based on https://www.w3schools.com/php/php_file_upload.asp
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';
$user = UserDB::getUserByToken($_COOKIE['Token']);
//Makes a directory for the user
if(!is_dir("../uploads/" . $user->userID))
    mkdir("../uploads/" . $user->userID);
$target_dir = "../uploads/" . $user->userID . "/";
$imageFileType = strtolower(pathinfo($target_dir . basename($_FILES["image"]["name"]),PATHINFO_EXTENSION));
$target_file = $target_dir . date("YmdHis") . "." . $imageFileType;
$alt = substr(basename($_FILES["image"]["name"]), 0, -4);
$error = 0;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $error = 0;
    } else {
        $error = 1;
    }
    // Check file size
    if ($_FILES["image"]["size"] > 5000000) {
        $error = 2;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error = 3;
    }
    if ($error == 0) {
        if(!(!isset($_POST['title']) && !isset($_POST['text']))){
            if(strlen($_POST['title']) <= 80){
                if(strlen($_POST['text']) <= 2000){
                    $user = UserDB::getUserByToken($_COOKIE['Token']);
                    $blog = new Blogpost($user->userID,$_POST['category'],$target_file,date("Y-m-d"),$_POST['title'],$_POST['text'],$alt);
                    BlogpostDB::addBlogpost($blog);
                    $data["success"] = true;
                    $data["error"] = null;
                }
            }
        }
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            header("location:../blog.php");
        } else {
            header("location:../makepost.php?error=" . $error);
        }
    }
    else {
        header("location:../makepost.php?error=" . $error);
    }
}
?>
