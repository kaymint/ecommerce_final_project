<?php

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/laptop.php';

require_once '../model/orders.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('receipt_info.html.twig');
$params = array();

$template->display($params);