<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 3:58 PM
 */

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/furniture.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('cart.html.twig');
$params = array();

getCartItemDetails();
$params['cartDetails'] = $_SESSION['cart_details'];
$params['subTotal'] = $_SESSION['sub_total'];

$params['currentPage'] = $_SERVER['PHP_SELF'];

if(isset($_SESSION['nItems'] )){
    $params['nItems'] = $_SESSION['nItems'];
}

$template->display($params);