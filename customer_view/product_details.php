<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 10:04 AM
 */

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/furniture.php';

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('product_details.html.twig');
$params = array();

$furniture = new furniture();


if(isset($_REQUEST['cmd'])){
    $cmd = intval($_REQUEST['cmd']);

    switch ($cmd){
        case 1:
            //customer_view by category
            if(isset($_REQUEST['fid'])){
                $fid = intval($_REQUEST['fid']);
                $result = $furniture->getProduct($fid);
                $item = $result->fetch_assoc();
                $params['item'] = $item;
            }

            break;
        case 2:
            //customer_view by brandname
            break;
        case 3:
            break;
        case 4:
            break;
    }
}


//stock


//categories
$result = $furniture->getCategories();
$cat = $result->fetch_all(MYSQLI_ASSOC);
$params['categories'] = $cat;


//brands
$result = $furniture->getBrands();
$brands = $result->fetch_all(MYSQLI_ASSOC);
$params['brands'] = $brands;

$params['currentPage'] = $_SERVER['PHP_SELF'];

if(isset($_SESSION['nItems'] )){
    $params['nItems'] = $_SESSION['nItems'];
}

$template->display($params);


