<?php

class User {
    public $userID;
    public $username;
    public $hash;
    public $email;
    public $userSince;
    public $description;

    public function __construct($username, $email, $userSince, $description, $userID = -1, $hash = null)
    {
        $this->userID = $userID;
        $this->username = htmlentities($username);
        $this->hash = $hash;
        $this->email = $email;
        $this->userSince = $userSince;
        $this->description = htmlentities($description);
    }


}

?>