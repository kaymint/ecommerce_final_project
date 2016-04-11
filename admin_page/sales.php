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
$template =$twig->loadTemplate('sales.html.twig');
$params = array();

if (isset($_GET['page'])) {
    $pageno = $_GET['page'];
} else {
    $pageno = 1;
}

$furniture = new furniture();
$orders = new orders();

$result = $orders->getSalesCount();

$count = $result->fetch_assoc();
$numrows = $count['totalCount'];

//3
$rows_per_page = 15;
$lastpage      = ceil($numrows/$rows_per_page);

//4
$pageno = (int)$pageno;
if ($pageno > $lastpage) {
    $pageno = $lastpage;
} // if
if ($pageno < 1) {
    $pageno = 1;
} // if

$params['inventory_count'] = $numrows;

//5
$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

$result = $orders->getSales();

//stock party
$stock = $result->fetch_all(MYSQLI_ASSOC);
$params['sales'] = $stock;


//get orders
$result = $orders->getNumOrders();
$nOrders = $result->fetch_assoc();
$params['order_count'] = $nOrders['numOrders'];

//get Total Sales
$result = $orders->getTotals();
$nTotals = $result->fetch_assoc();
$params['totalSales'] = $nTotals['totalSales'];

//get sales
$result = $orders->getNumSales();
$nSales = $result->fetch_assoc();
$params['sales_count'] = $nOrders['numSales'];

$params['currentPage'] = $_SERVER['PHP_SELF'];
$params['page'] = $pageno;
$params['totalPages'] = $lastpage;

$params['admin_username'] = $_SESSION['admin_username'];
$params['admin_id'] = $_SESSION['admin_id'];
$params['admin_firstname'] = $_SESSION['admin_firstname'];
$params['admin_lastname'] = $_SESSION['admin_lastname'];

$template->display($params);