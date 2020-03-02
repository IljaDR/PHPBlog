<?php

class Blogpost {
    public $blogpostID;
    public $userID;
    public $categoryID;
    public $picturePath;
    public $date;
    public $title;
    public $text;
    public $alt;

     public function __construct($userID, $categoryID, $picturePath, $date, $title, $text, $alt = "", $blogpostID = null)
    {
        $this->blogpostID = $blogpostID;
        $this->userID = $userID;
        $this->categoryID = $categoryID;
        $this->picturePath = $picturePath;
        $this->date = $date;
        $this->title = htmlentities($title);
        $this->text = htmlentities($text);
        $this->alt = htmlentities($alt);
    }
}

?>