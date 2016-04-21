<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/11/16
 * Time: 5:37 PM
 */

session_start();
require_once '../model/orders.php';
require_once '../model/laptop.php';
require_once '../customer_view/validation_functions.php';

if(isset($_REQUEST['cmd'])){
    $cmd = intval($_REQUEST['cmd']);

    switch($cmd){

        case 1:
            //Add
            pay();
            break;
        case 2:
            //cofirmation email
            orderConfirmation();
            break;
        case 3:
            placeOrder();
            break;

    }
}

function placeOrder(){
    if(isset($_POST['rec_email']) && isset($_POST['rec_firstname']) &&
        isset($_POST['rec_lastname']) && isset($_POST['rec_phone'])
        && isset($_POST['rec_address1'])){

        $order = new order();
        $laptop = new laptop();

        $total = doubleval($_SESSION['overallTotal']);

        $rec_email = sanitize_string($_POST['rec_email']);
        $rec_firstname = sanitize_string($_POST['rec_firstname']);
        $rec_lastname = sanitize_string($_POST['rec_lastname']);
        $rec_phone = sanitize_string($_POST['rec_phone']);
        $rec_address1 = sanitize_string($_POST['rec_address1']);
        $rec_address2 = sanitize_string($_POST['rec_address2']);
        $rec_country = sanitize_string($_POST['rec_country']);

        $validator = "";

        $validator[]=validateFirstName($rec_firstname);
        $validator[]=validateLastName($rec_lastname);
        $validator[]=validateLastName($rec_email);


        if(strlen($validator[0]) > 0){
            $_SESSION['validationMessage'] = $validator;
            $prev_page = $_SERVER['HTTP_REFERER'];
            header("Location: {$prev_page}");
        }

        $order->addReceipt($total, $rec_address1, $rec_address2, $rec_phone, $rec_email, $rec_country, $rec_firstname, $rec_lastname);
        $rec_id = $order->get_insert_id();


        $params['receipt_id'] = $rec_id;

        $ind_item = $_SESSION['cart_details'];

        $_SESSION['rec_email'] = $rec_email;
        $_SESSION['rec_firstname'] = $rec_firstname;
        $_SESSION['rec_lastname'] = $rec_lastname;
        $_SESSION['rec_phone'] = $rec_phone;
        $_SESSION['rec_address1'] = $rec_address1;
        $_SESSION['rec_address2'] = $rec_address2;
        $_SESSION['rec_country'] = $rec_country;
        $_SESSION['receipt_id'] = $rec_id;


        foreach($ind_item as $value){
            $fid = $value['laptop_id'];
            $cost = $value['itemTotal'];
            $qty = $value['count'];
            $onhand = $value['qty'] - $qty;
            if($onhand < 0){
                $onhand = 0;
            }
            $res = $order->addOrder($rec_id, $fid, $cost, $qty);
            $laptop->updateQuantity($fid, $onhand);
        }

        orderConfirmation();
    }
}

function pay(){
    if(isset($_POST['rid'])){

        $rid = sanitize_string($_POST['rid']);

        $orders = new order();
        $res = $orders->updateReceipt($rid);
        if($res != false){
            header("Location: ../admin_page/sales.php");
        }else{
            header("Location: ../order_details.php?rid={$rid}");
        }
    }
}


/**
 *
 */
function orderConfirmation(){
    if(isset($_SESSION['rec_email'])){

        $com_name = $_SESSION['com_name'];
        $email = $_SESSION['rec_email'];
        $name = $_SESSION['rec_firstname']." ".$_SESSION['rec_lastname'];
        $address = $_SESSION['rec_address1'];
        $details = $_SESSION['cart_details'];
        $recipt_id = $_SESSION['receipt_id'];
        $overall = $_SESSION['overallTotal'];

        $logo = '../customer_view/img/logo/logo.png';

        $message = "<img src='../customer_view/img/logo/logo.png'>";
        $message.= "<h3 style='font-style: italic'>Billed to:</h3>";
        $message.= "<h4>{$name} of {$com_name}</h4>";
        $message .= "<h5>Shipping Address: {$address}</h5>";
        $message .= "<br><br>";


        $message .= "<table class='table-bordered'><tr><th>Item</th><th>Cost</th><th>Qty</th><th>Sub Total</th></tr>";
        foreach($details as $row){
            $message .= "<tr><td>{$row['product_name']}</td>";
            $message .= "<td>${$row['price']}</td>";
            $message .= "<td>{$row['count']}</td>";
            $message .= "<td>${$row['itemTotal']}</td></tr>";
        }
        $message .= "</table>";

        $message .= "<br><br>";
        $message .= "<h3>Total: ${$overall}</h3>";

        sendMail($email, $message, $name, 'Exclusive Furniture Order Confirmation');

    }
}

function sendMail($cust_mail, $message, $cust_name, $subject){

    date_default_timezone_set('Etc/UTC');
    require 'phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.office365.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'kenneth.mensah@ashesi.edu.gh';
    $mail->Password = 'kaymint146';
    $mail->setFrom('kenneth.mensah@ashesi.edu.gh', 'Best Notbooks');
    $mail->addAddress($cust_mail, $cust_name);
    $mail->Subject = $subject;
    $mail->msgHTML($message);
    $mail->AltBody = 'Payment Confirmation';
    if($mail->send()){
        header("Location: ../customer_view/receipt_info.php");
    }else{
        header("Location: ../customer_view/products.php");
    }
}

//sanitize command sent
function sanitize_string($val){
    $val = stripslashes($val);
    $val = strip_tags($val);
    $val = htmlentities($val);

    return $val;
}
