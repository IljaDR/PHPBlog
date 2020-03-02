<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/database/CRUD/UserDB.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data["success"] = false;
    $data["error"] = "Your credentials are incorrect";
    if(UserDB::checkUsernameIsUsed($_POST['username'])){
        $data["error"] = "This username is already being used!";
    }
    else if(UserDB::checkEmailIsUsed($_POST['mail'])){
        $data["error"] = "This email is already being used!";
    }
    else{
        if(!(!isset($_POST['mail']) && !isset($_POST['password']) && !isset($_POST['username']))){
            if(preg_match("/^[a-zA-Z0-9]*$/", $_POST['username'])){
                if(strlen($_POST['username']) < 20){
                    if(isset($_POST['description'])){
                        UserDB::createUser($_POST['mail'],$_POST['password'],$_POST['username'],$_POST['description']);
                    }
                    else{
                        UserDB::createUser($_POST['mail'],$_POST['password'],$_POST['username']);
                    }
                    UserDB::setToken($_POST['password'],$_POST['mail']);
                    setcookie("Token", UserDB::getToken($_POST['password'],$_POST['mail']), time()+60*60*24*6004, "/");
                    $data["success"] = true;
                    $data["error"] = null;
                }
            }
        }
    }
    echo json_encode($data);
}
else {
    header("location:../blog.php");
}

?>
