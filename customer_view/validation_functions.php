<?php
//error_reporting(E_ERROR | E_PARSE);
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/4/16
 * Time: 10:40 PM
 */


function validateFirstName($input){
    $pattern = '/^[A-Z]([-\']?[a-z]+)/';
    if(strlen($input) == 0){
        return "** Nothing was entered for Firstname \r\n";
    }
    elseif(!preg_match($pattern, $input)){
        return "** Firstname must start with Caps\r\n";
    }else{
        return "";
    }
}

function validateLastName($input){
    $pattern = '/^[A-Z]([-\']?[a-z]+)/';
    if(strlen($input) == 0){
        return "** Nothing was entered for Lastname \r\n";
    }
    elseif(!preg_match($pattern, $input)){
        return "** Lastname must start with Caps\r\n";
    }else{
        return "";
    }
}


function validateEmail($input){
    $pattern = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    if(strlen($input) == 0){
        return "** Nothing was entered for Email \r\n";
    }
    elseif(!preg_match($pattern, $input)){
        return "** Please Enter Valid Email\r\n";
    }else{
        return "";
    }
}

function validateDOB($input){

}


function validateAddress($input){

}

function validateWebAddress($input){

}

function validateSalary($input){

}

function validateLargeText($input){

}

function validatePassword($input){

}