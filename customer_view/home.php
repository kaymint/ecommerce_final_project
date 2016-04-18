<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 10:04 AM
 */

require_once 'shopping_cart_control.php';

if (isset($_GET['page'])) {
    $pageno = $_GET['page'];
} else {
    $pageno = 1;
}

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/laptop.php';

Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$template =$twig->loadTemplate('home.html.twig');
$params = array();

$laptop = new laptop();

$result = $laptop->getStockCount();

$count = $result->fetch_assoc();
$numrows = $count['totalCount'];

//3
$rows_per_page = 8;
$lastpage      = ceil($numrows/$rows_per_page);

//4
$pageno = (int)$pageno;
if ($pageno > $lastpage) {
    $pageno = $lastpage;
} // if
if ($pageno < 1) {
    $pageno = 1;
} // if

//5
$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

$result = $laptop->viewAllLaptops($limit);

//stock party
$stock = $result->fetch_all(MYSQLI_ASSOC);
$params['laptops'] = $stock;

//categories
$result = $laptop->getBrands();
$brands = $result->fetch_all(MYSQLI_ASSOC);
$params['brands'] = $brands;




$limit2 = 'LIMIT 4';
//getByCategories
$allCats = array();
foreach($brands as $value){
    $res = $laptop->viewLaptopsByBrand($value['brand_id'], $limit2);
    $catRes = $res->fetch_all(MYSQLI_ASSOC);
    $allCats[$value['brand_id']] = $catRes;
}
$params['catTab'] = $allCats;

$params['currentPage'] = $_SERVER['PHP_SELF'];
$params['page'] = $pageno;
$params['totalPages'] = $lastpage;

if(isset($_SESSION['nItems'])){
    $params['nItems'] = $_SESSION['nItems'];
    getCartItemDetails();
    $params['cartDetails'] = $_SESSION['cart_details'];
    $params['sub_total'] = $_SESSION['sub_total'];
}

$template->display($params);



