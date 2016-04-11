<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/27/16
 * Time: 4:23 PM
 */

session_start();
if(isset($_REQUEST['theme'])){
    $theme = intval($_REQUEST['theme']);
    $_SESSION['theme'] = $theme;
    header("Location: home.php");
}