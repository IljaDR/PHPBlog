<?php
if(isset($_COOKIE['Token'])){
    var_dump($_COOKIE['Token']);
    setcookie('Token', "", time()-1, "/");
    unset($_COOKIE['Token']);
    var_dump($_COOKIE['Token']);
}
header("location:../blog.php");
?>