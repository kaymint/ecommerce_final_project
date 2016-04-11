<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/27/16
 * Time: 3:44 PM
 */
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
}