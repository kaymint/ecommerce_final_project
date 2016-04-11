<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/26/16
 * Time: 6:56 PM
 */

session_start();

unset($_SESSION['com_name']);
unset($_SESSION['rec_email']);
unset($_SESSION['rec_firstname']);
unset($_SESSION['rec_lastname']);
unset($_SESSION['rec_phone']);
unset($_SESSION['rec_address1']);
unset($_SESSION['rec_address2']);
unset($_SESSION['rec_country']);
unset($_SESSION['receipt_id']);
unset($_SESSION['overallTotal']);
unset($_SESSION['sub_total']);
unset($_SESSION['discount']);
unset($_SESSION['cart_details']);
unset($_SESSION['cart_ids']);
unset( $_SESSION['nItems']);

header('Location: home.php');