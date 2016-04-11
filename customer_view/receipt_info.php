<?php

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/furniture.php';

require_once '../model/orders.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('receipt_info.html.twig');
$params = array();

$params['receipt_id'] = $_SESSION['receipt_id'];

$template->display($params);