<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 7:28 PM
 */

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/laptop.php';

require_once '../model/orders.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('checkout.html.twig');
$params = array();

getCartItemDetails();
$params['cartDetails'] = $_SESSION['cart_details'];
$params['subTotal'] = $_SESSION['sub_total'];

//discount
if($_SESSION['sub_total'] > 1000){
    $params['overallTotal'] = number_format(((($_SESSION['sub_total'] * .05) + $_SESSION['sub_total']) * .95),2);
    $_SESSION['overallTotal'] = $params['overallTotal'];
    $params['discount'] = 5;
    $_SESSION['discount'] = 5;
    $params['tax'] = number_format(($_SESSION['sub_total'] * .05), 2);
    $_SESSION['tax'] = $params['tax'];
}else{
    $params['overallTotal'] = number_format((($_SESSION['sub_total'] * .05) + $_SESSION['sub_total']), 2) ;
    $_SESSION['overallTotal'] = $params['overallTotal'];
    $params['discount'] = 0;
    $_SESSION['discount'] = 0;
    $params['tax'] = number_format(($_SESSION['sub_total'] * .05), 2);
    $_SESSION['tax'] = $params['tax'];
}

$params['currentPage'] = $_SERVER['PHP_SELF'];

if(isset($_SESSION['nItems'])){
    $params['nItems'] = $_SESSION['nItems'];
    getCartItemDetails();
    $params['cartDetails'] = $_SESSION['cart_details'];
    $params['sub_total'] = $_SESSION['sub_total'];
}


$template->display($params);