<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/25/16
 * Time: 7:28 PM
 */

require_once 'shopping_cart_control.php';

require_once 'Twig-1.x/lib/Twig/Autoloader.php';

require_once '../model/furniture.php';

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
    $params['overallTotal'] = ($_SESSION['sub_total'] * .95);
    $_SESSION['overallTotal'] = $params['overallTotal'];
    $params['discount'] = 5;
    $_SESSION['discount'] = 5;
}else{
    $params['overallTotal'] = ($_SESSION['sub_total']);
    $_SESSION['overallTotal'] = $params['overallTotal'];
    $params['discount'] = 0;
    $_SESSION['discount'] = 0;
}

$params['currentPage'] = $_SERVER['PHP_SELF'];

if(isset($_SESSION['nItems'] )){
    $params['nItems'] = $_SESSION['nItems'];
}

$params['username']= $_SESSION['username'];
$params['fname']= $_SESSION['fname'];
$params['lname']= $_SESSION['lname'] ;
$params['email']= $_SESSION['email'] ;
$params['address']= $_SESSION['address'] ;
$params['phone']= $_SESSION['phone'] ;
$params['title']= $_SESSION['title'] ;

if(isset($_POST['rec_email']) && isset($_POST['rec_firstname']) &&
isset($_POST['rec_lastname']) && isset($_POST['rec_phone'])
    && isset($_POST['rec_address1'])){

    $order = new orders();
    $f = new furniture();

    $cid = $_SESSION['cust_id'];
    $total = $_SESSION['overallTotal'];

    $com_name = $_POST['com_name'];
    $rec_email = $_POST['rec_email'];
    $rec_firstname = $_POST['rec_firstname'];
    $rec_lastname = $_POST['rec_lastname'];
    $rec_phone = $_POST['rec_phone'];
    $rec_address1 = $_POST['rec_address1'];
    $rec_address2 = $_POST['rec_address2'];
    $rec_country = $_POST['rec_country'];

    $order->addReceipt($cid, $total, $rec_address1, $rec_phone, $rec_address2, $rec_firstname, $rec_lastname,
        $rec_email, $rec_country, $com_name);
    $rec_id = $order->get_insert_id();

    $params['receipt_id'] = $rec_id;

    $ind_item = $_SESSION['cart_details'];

    $_SESSION['com_name'] = $com_name;
    $_SESSION['rec_email'] = $rec_email;
    $_SESSION['rec_firstname'] = $rec_firstname;
    $_SESSION['rec_lastname'] = $rec_lastname;
    $_SESSION['rec_phone'] = $rec_phone;
    $_SESSION['rec_address1'] = $rec_address1;
    $_SESSION['rec_address2'] = $rec_address2;
    $_SESSION['rec_country'] = $rec_country;
    $_SESSION['receipt_id'] = $rec_id;


    foreach($ind_item as $value){
        $fid = $value['furniture_id'];
        $cost = $value['itemTotal'];
        $qty = $value['count'];
        $onhand = $value['onhand'] - $qty;
        if($onhand < 0){
            $onhand = 0;
        }
        $res = $order->addOrder($rec_id, $fid, $cid, $cost, $qty);
        $f->updateQuantity($fid, $onhand);
    }

    header("Location: ../controller/order_controller.php?cmd=2");
}


$template->display($params);