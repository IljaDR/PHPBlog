<?php

class Comment{
  public $commentID;
  public $userID;
  public $blogpostID;
  public $title;
  public $text;
  public $date;

    public function __construct($userID, $title, $text, $date, $blogpostID = null, $commentID = null){
        $this->commentID = $commentID;
        $this->userID = $userID;
        $this->blogpostID = $blogpostID;
        $this->title = htmlentities($title);
        $this->text = htmlentities($text);
        $this->date = $date;
    }
}
?>