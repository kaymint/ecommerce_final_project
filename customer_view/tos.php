<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/21/16
 * Time: 6:11 PM
 */

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/laptop.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('tos.html.twig');
$params = array();

$template->display($params);