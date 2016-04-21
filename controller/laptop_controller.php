<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/12/16
 * Time: 1:29 AM
 */
session_start();
require_once '../model/laptop.php';

if(isset($_REQUEST['cmd'])){
    $cmd = intval($_REQUEST['cmd']);

    switch($cmd){

        case 1:
            //Add
            addInventory();
            break;
        case 2:
            //Update
            updateInventory();
            break;


    }
}


function updateInventory(){

    if(isset($_POST['disp']) && isset($_POST['name']) && isset($_POST['brand'])
        && isset($_POST['hd']) && isset($_POST['ram']) && isset($_POST['proc']) && isset($_POST['col'])
        && isset($_POST['os']) && isset($_POST['lid'])){

        $laptop = new laptop();

        $display = sanitize_string($_POST['disp']);
        $brand_id = sanitize_string($_POST['brand']);
        $hard_drive = sanitize_string($_POST['hd']);
        $processor = sanitize_string($_POST['proc']);
        $name = sanitize_string($_POST['name']);
        $ram = sanitize_string($_POST['ram']);
        $color = sanitize_string($_POST['col']);
        $os = sanitize_string($_POST['os']);
        $special_features = sanitize_string($_POST['spec']);
        $qty = sanitize_string($_POST['onhand']);
        $cost = sanitize_string($_POST['cost']);
        $laptop_id = sanitize_string($_POST['lid']);


        $laptop->updateLaptop($brand_id, $display, $hard_drive, $processor, $ram, $os, $name, $color,
                             $special_features, $laptop_id);
        $res = $laptop->updateInventory($laptop_id, $qty,  $cost);

        if($res != false){
            $_SESSION['message'] = 'Updated Succesfully';
            header("Location: ../admin_page/home.php");
        }else{
            $_SESSION['message'] = 'Update Unsuccesful';
            header("Location: ../admin_page/home.php");
        }
    }
}


function addInventory(){


    if(isset($_POST['disp']) && isset($_POST['name']) && isset($_POST['brand'])
        && isset($_POST['hd']) && isset($_POST['ram']) && isset($_POST['proc']) && isset($_POST['col'])
        && isset($_POST['os'])){

        $laptop = new laptop();

        $display = $_POST['disp'];
        $brand_id = $_POST['brand'];
        $hard_drive = $_POST['hd'];
        $processor = $_POST['proc'];
        $name = $_POST['name'];
        $ram = $_POST['ram'];
        $color = $_POST['col'];
        $os = $_POST['os'];
        $special_features = $_POST['spec'];
        $img = '';
        if(isset($_SESSION['filepath'])){
            $img = $_SESSION['filepath'];
        }
        $qty = $_POST['onhand'];
        $cost = $_POST['cost'];



        $laptop->addLaptop($brand_id, $display, $hard_drive, $processor, $ram, $os, $name, $color, $special_features,
            $img);
        $lid = $laptop->get_insert_id();
        $res = $laptop->addInventoryDetails($lid, $qty,  $cost);

        if($res != false){
            $_SESSION['message'] = 'Added Succesfully';
            header("Location: ../customer_view/admin_page/home.php");
        }else{
            $_SESSION['message'] = 'Add Unsuccesful';
            header("Location: ../customer_view/admin_page/add.php");
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