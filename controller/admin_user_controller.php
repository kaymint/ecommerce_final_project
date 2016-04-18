<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/27/16
 * Time: 2:40 PM
 */

session_start();
require_once '../model/admin.php';
require_once 'security.php';

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
        $user = sanitize_string($_POST['user']);
        $pass = sanitize_string($_POST['pass']);

        echo $pass;
        $testObj = new admin();

        $result = $testObj->authenticateUser($pass, $user);

        if($result == false){
            header("Location: ../customer_view/admin_page/login.php");
        }else{
            $_SESSION['admin_username'] = $result['username'];
            $_SESSION['admin_id'] = $result['admin_id'];
            $_SESSION['admin_firstname'] = $result['firstname'];
            $_SESSION['admin_lastname'] = $result['lastname'];

            header("Location: ../customer_view/admin_page/home.php");
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