<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(strlen($_POST['category']) < 40 && strlen($_POST['category']) > 3){
        $data["success"] = true;
        CategoryDB::addCategory($_POST['category'], $_COOKIE['Token']);
    }
    else{
        $data["success"] = false;
        $data["error"] = "Your input is invalid";
    }
    echo json_encode($data);
}
else {
    header("location:../blog.php");
}


//UserDB::checkCredentials($_POST['mail'], $_POST['password'])
?>
