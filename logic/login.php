<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/CRUD/UserDB.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(date(UserDB::checkCredentials($_POST['mail'], $_POST['password']))){
        $data["success"] = true;
        UserDB::setToken($_POST['password'],$_POST['mail']);
        if(isset($_POST['check']))
            setcookie("Token", UserDB::getToken($_POST['password'],$_POST['mail']), time()+60*60*24*6004, "/");
        else{
            setcookie("Token", UserDB::getToken($_POST['password'],$_POST['mail']), time()+60*60*2, "/");
        }
    }
    else{
        $data["success"] = false;
        $data["error"] = "The credentials are incorrect";
    }
    echo json_encode($data);
}
else {
    header("location:../blog.php");
}


//UserDB::checkCredentials($_POST['mail'], $_POST['password'])
?>
