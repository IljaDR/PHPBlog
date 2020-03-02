<?php
class Category {
    public $categoryID;
    public $categoryName;

    public function __construct($categoryName, $categoryID = null){
        $this->categoryID = $categoryID;
        $this->categoryName = $categoryName;
    }
}

?>