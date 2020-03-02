<?php

function fixDate($date){
    return DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y');
}

function shortText($text){
    if(strlen($text) > 200)
        return substr($text, 0, 197) . "...";
    else
        return $text;
}

function shorterText($text){
    if(strlen($text) > 40)
        return substr($text, 0, 37) . "...";
    else
        return $text;
}

?>