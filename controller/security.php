<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/13/16
 * Time: 1:12 PM
 */

/**
 * @param $l
 * @param string $c
 * @return string
 */
function secretHash($l, $c = 'abcdefghijklmnopqrstuvwxyz1234567890!@%'){
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}

/**
 * @return string
 */
function assignKey(){
    $hash = secretHash(6);
    $now = explode(' ', microtime())[1];
    $secret = $hash . $now;
    return $secret;
}

/**
 * @return string
 */
function assignSecret(){
    return secretHash(8);
}

/**
 * @param $value
 * @return bool|string
 */
function encrypt($value){
    $salt1 = '!bt';
    $salt2 = '#th';
    $value = $salt1.$value.$salt2;
    return password_hash($value, PASSWORD_BCRYPT);
}

function verifyKey($value, $hash){
    $salt1 = '!bt';
    $salt2 = '#th';
    $value = $salt1.$value.$salt2;
    return password_verify($value, $hash);
}

/**
 *
 */
function twoWayEncrypt($data){

    $enc_key = '25c6c7ff35b9979b151f2136cd13b0ff';
    $method = 'AES-128-CBC';
    $options = 0;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    $encrypted = openssl_encrypt($data, $method, $enc_key, $options, $iv);
    return $iv.$encrypted;
}

$result = twoWayEncrypt('Its works or not');
echo $result;


function twoWayDecrypt($data){
    $enc_key = '25c6c7ff35b9979b151f2136cd13b0ff';
    $method = 'AES-128-CBC';
    $options = 0;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = substr($data, 0, $iv_size);
    echo "<br>".$iv;
    $decrypted = openssl_decrypt();
}

