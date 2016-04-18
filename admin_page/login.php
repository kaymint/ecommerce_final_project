<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/27/16
 * Time: 1:30 AM
 */

require_once '../customer_view/Twig-1.x/lib/Twig/Autoloader.php';

Twig_Autoloader::register();



$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('login.html.twig');
$params = array();

$template->display($params);