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
    if(isset($_POST['type']) && isset($_POST['name']) && isset($_POST['brand'])
        && isset($_POST['cat']) && isset($_POST['fid'])){

        $furniture = new furniture();

        $fid = intval($_POST['fid']);
        $type = $_POST['type'];
        echo "type :".$type;
        $brand = $_POST['brand'];
        echo "brand :".$brand;
        $cat = $_POST['cat'];
        echo "id :".$cat;
        $name = $_POST['name'];
        $image = '';
        if(isset($_SESSION['filepath'])){
            $image = $_SESSION['filepath'];
        }
        $qty = $_POST['onhand'];
        $cost = $_POST['cost'];
        $desc = $_POST['desc'];




        $furniture->updateFurniture($type, $name, $desc, $cat,$brand, $image, $fid);
        $res = $furniture->updateInventory($fid, $qty,  $cost);

        if($res != false){
            $_SESSION['message'] = 'Added Succesfully';
            header("Location: ../customer_view/admin_page/home.php");
        }else{
            $_SESSION['message'] = 'Add Unsuccesful';
            header("Location: ../customer_view/admin_page/home.php");
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
            header("Location: ../customer_view/admin_page/add.php");
        }else{
            $_SESSION['message'] = 'Add Unsuccesful';
            header("Location: ../customer_view/admin_page/add.php");
        }
    }
}