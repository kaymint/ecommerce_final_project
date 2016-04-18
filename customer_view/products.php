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
$template =$twig->loadTemplate('products.html.twig');
$params = array();

$laptop = new laptop();


function getResult($laptop){


    $value = array();
    if(isset($_REQUEST['vt'])) {

        $vt = intval($_REQUEST['vt']);
        $value['vt'] = $vt;

        switch ($vt) {
            case 1:
                //category
                if (isset($_REQUEST['brand'])) {
                    $brand = intval($_REQUEST['brand']);
                    $result = $laptop->getStockBrandCount($brand);
                    $value['pType'] = 'brand';
                    $value['pageIndex'] = $brand;
                    $value['result'] = $result;
                    return $value;
                }
                break;
            case 2:
                //all
                $result = $laptop->getStockCount();
                $value['result'] = $result;
                return $value;
                break;
            case 3:
                //simple search
                if(isset($_REQUEST['st'])){
                    $st = $_REQUEST['st'];
                    $result = $laptop->getSimpleSearchCount($st);
                    $value['pType'] = 'st';
                    $value['pageIndex'] = $st;
                    $value['result'] = $result;
                    return $value;
                }
                break;
            case 4:
                //advanced search
                if(isset($_REQUEST['stband']) || isset($_REQUEST['stname'])){
                    $stbrand = $_REQUEST['stband'];
                    $stname = $_REQUEST['stname'];
                    $result = $laptop->getAdvancedSearchCount($stbrand, $stname);
                    $value['pType'] = 'stband';
                    $value['pageIndex'] = $stbrand;
                    $value['stname'] = 'stname';
                    $value['stNameIndex'] = $stname;
                    $value['result'] = $result;
                    return $value;
                }
                break;
            default:
                //all
                $result = $laptop->getStockCount();
                $value['result'] = $result;
                return $value;
                break;
        }
    }else{
        $result = $laptop->getStockCount();
        $value['result'] = $result;
        return $value;
    }
}


$value = getResult($laptop);

$result = $value['result'];

if(isset($value['pageIndex']) && isset($value['pType']) && isset($value['vt'])){
    $params['pageIndex'] = $value['pageIndex'];
    $params['pType'] = $value['pType'];
    $params['vt'] = $value['vt'];
}

if(isset($value['stname'])){
    $params['stname']= $value['stname'];
    $params['stNameIndex']= $value['stNameIndex'];
}

$count = $result->fetch_assoc();
$numrows = $count['totalCount'];


//3
$rows_per_page = 9;
$lastpage      = ceil($numrows/$rows_per_page);

//4
$pageno = (int)$pageno;
if ($pageno > $lastpage) {
    $pageno = $lastpage;
} // if
if ($pageno < 1) {
    $pageno = 1;
} // if

$result = '';
//5
$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

if(isset($_REQUEST['cmd'])){
    $cmd = intval($_REQUEST['cmd']);

    switch ($cmd){
        case 1:
            //customer_view by brandname
            if(isset($_REQUEST['brand'])){
                $brand = intval($_REQUEST['brand']);
                $result = $laptop->viewLaptopsByBrand($brand, $limit);
                $params['pageType'] = 'brand';
            }
            break;
        case 2:
            //customer_view search
            if(isset($_REQUEST['st'])){
                $name = $_REQUEST['st'];
                $result = $laptop->getSimpleSearch($name);
                $params['pageType'] = 'st';
            }
            break;
        case 3:
            if(isset($_REQUEST['stband']) || isset($_REQUEST['stname'])) {
                $stbrand = $_REQUEST['stband'];
                $stname = $_REQUEST['stname'];
                $result = $laptop->advancedSearch($stbrand, $stname);
            }
            break;
        default:
            $result = $laptop->viewAllLaptops($limit);
            $params['pageType'] = 'all';
            break;
    }
}else{
    $result = $laptop->viewAllLaptops($limit);
    $params['pageType'] = 'all';
}


//stock
$stock = $result->fetch_all(MYSQLI_ASSOC);
$params['laptops'] = $stock;


//brands
$result = $laptop->getBrands();
$brands = $result->fetch_all(MYSQLI_ASSOC);
$params['brands'] = $brands;


$params['currentPage'] = $_SERVER['PHP_SELF'];

//pages
$params['page'] = $pageno;
$params['totalPages'] = $lastpage;

if(isset($_SESSION['nItems'])){
    $params['nItems'] = $_SESSION['nItems'];
    getCartItemDetails();
    $params['cartDetails'] = $_SESSION['cart_details'];
    $params['sub_total'] = $_SESSION['sub_total'];
}

$template->display($params);


