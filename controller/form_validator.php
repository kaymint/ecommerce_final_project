<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/4/16
 * Time: 10:30 PM
 */
session_start();

require_once 'validation_functions.php';

$validator = "";

if(isset($_GET['fullname'])){
    $fullname = $_GET['fullname'];
    $validator[]=validateFullName($fullname);
}

if(isset($_GET['age'])){
    $age = $_GET['age'];
    $validator[]=validateAge($age);
}


if(!strlen($validator[0]) > 0){
    $_SESSION['v'] = $validator;
    header("Location: form1.php");
}else{
    echo "valid inputs";
}
