<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 3:58 PM
 */
require_once 'valid_session_handler.php';

require_once '../Twig-1.x/lib/Twig/Autoloader.php';

require_once '../../model/furniture.php';

require_once '../../model/orders.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('order_details.html.twig');
$params = array();

$orders = new orders();

if(isset($_REQUEST['rid'])){
    $rid = intval($_REQUEST['rid']);

    $result = $orders->getReceiptDetails($rid);
    $details = $result->fetch_all(MYSQLI_ASSOC);
    $params['details'] = $details;
}

$params['admin_username'] = $_SESSION['admin_username'];
$params['admin_id'] = $_SESSION['admin_id'];
$params['admin_firstname'] = $_SESSION['admin_firstname'];
$params['admin_lastname'] = $_SESSION['admin_lastname'];



$template->display($params);