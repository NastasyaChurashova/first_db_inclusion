<?php

include 'DB.php';
$db= new DB();

if (array_key_exists('name', $_POST) && array_key_exists('comment', $_POST)){
    // TO DO: add validation for sql 
    $db->add($_POST['name'], $_POST['comment']);
    echo "success";
}

header('location: /comments/');