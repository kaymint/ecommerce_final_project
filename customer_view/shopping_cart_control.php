<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Basic functions:
 *      1. Add To Cart
 *      2. Decrease Count
 *      3. Calculate Total
 *      5. Remove Item From Cart
 *      4. Empty Cart
 * Date: 3/25/16
 * Time: 4:09 PM
 */
session_start();

/**
 * Session variable definitions
 *      1. cart_ids = ids of items in the cart
 *      2. id = id of item
 *      3. func = function to perform: -> 1=Add, 2=Subtract, 3=Remove Item
 *                                        4=Empty Cart,
 *      4. subtotal
 *      5. cart_details
 */

require_once '../model/furniture.php';

//cart ids
if(!isset($_SESSION['cart_ids'])){
    $_SESSION['cart_ids'] = array();
    $_SESSION['nItems'] = 0;
    var_dump($_SESSION['cart_ids']);
}

//take away errors
if(isset($_SESSION['cart_ids'][''])){
    unset($_SESSION['cart_ids']['']);
}

if(isset($_REQUEST['func'])){
    $func  = intval($_REQUEST['func']);
    switch ($func){
        case 1:
            //Add to cart
            if(isset($_GET['id'])){
                if(!array_key_exists($_GET['id'], $_SESSION['cart_ids'])){
                    $_SESSION['cart_ids'][$_GET['id']] = 1;
                    $_SESSION['nItems']++;
                }else{
                    $_SESSION['cart_ids'][$_GET['id']]++;
                    $_SESSION['nItems']++;
                }
            }

            break;
        case 2:
            //Decrement
            if(isset($_GET['id'])){
                if(array_key_exists($_GET['id'], $_SESSION['cart_ids'])){
                    $key = intval($_GET['id']);
                    $_SESSION['cart_ids'][$key]--;
                    $_SESSION['nItems']--;
                    $count = $_SESSION['cart_ids'][$key];
                    if($count <= 0){
                        unset($_SESSION['cart_ids'][$key]);
                    }
                }
            }

            break;
        case 3:
            //Remove Item

            if(isset($_GET['id'])){
                if(array_key_exists( $_GET['id'], $_SESSION['cart_ids'])){
                    $key = intval($_GET['id']);

                    $_SESSION['nItems'] -= $_SESSION['cart_ids'][$key];
                    unset($_SESSION['cart_ids'][$key]);
                }
            }

            break;
        case 4:
            //Empty Cart
            if(isset($_GET['id'])){
                $_SESSION['nItems'] = 0;
                unset($_SESSION['cart_ids']);
            }
            break;
    }
}


function getCartItemDetails(){

    if(isset($_SESSION['cart_ids'])){
        $_SESSION['sub_total'] = 0;
        $_SESSION['cart_details'] = array();
        $obj = new furniture();
        foreach($_SESSION['cart_ids'] as $key => $count){
            $res = $obj->getProduct($key);
            $cart_row = $res->fetch_assoc();
            $_SESSION['cart_details'][$key] = $cart_row;
            $_SESSION['cart_details'][$key]['count'] = $count;
            $_SESSION['cart_details'][$key]['itemTotal'] = ($count * $cart_row['cost']);
            $_SESSION['sub_total'] += ($count * $cart_row['cost']);
        }
    }
}

