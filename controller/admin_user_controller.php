<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/27/16
 * Time: 2:40 PM
 */

session_start();
require_once '../model/admin.php';

if(isset($_REQUEST['cmd'])){
    $cmd = intval($_REQUEST['cmd']);

    switch($cmd){

        case 1:
            //Add
            login();
            break;
        case 2:
            //Update
            break;

    }
}

function login(){
    if(isset($_POST['user']) && isset($_POST['pass'])){
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        echo $pass;
        $testObj = new admin();
        $result = $testObj->loginUser('N.Amanquah', 'N.Amanquah');
        $row = $result->fetch_assoc();


        if(count($row) > 0){
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_firstname'] = $row['firstname'];
            $_SESSION['admin_lastname'] = $row['lastname'];

            header("Location: ../customer_view/admin_page/home.php");
        }else{
            header("Location: ../customer_view/admin_page/login.php");
        }
    }
}

//sanitize command sent
function sanitize_string($val){
    $val = stripslashes($val);
    $val = strip_tags($val);
    $val = htmlentities($val);

    return $val;
}