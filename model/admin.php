<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/24/16
 * Time: 12:21 AM
 */

require_once 'adb_object.php';

class admin extends adb_object{

    function __construct()
    {
        parent::__construct();
    }


    /**
     * @param $username
     * @param $password
     * @param $firstname
     * @param $lastname
     * @return bool|mysqli_stmt
     */
    function addAdmin($username, $password, $firstname, $lastname){

        $password = encrypt($password);
        //sql query
        $str_query = "INSERT INTO admin(username, password, firstname, lastname)
                      VALUES (?,?,?,?)";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("ssss", $username, $password, $firstname, $lastname);


        $stmt->execute();

        return $stmt;
    }

    /**
     * @param $username
     * @param $password
     * @return bool|mysqli_result
     */
    function loginUser($username , $password){

        $password = encrypt($password);

        $str_query = "SELECT * FROM ecommerce_furniture.admin WHERE username = ? and password = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("ss", $username, $password);

        $stmt->execute();

        return $stmt->get_result();
    }
}


function encrypt($pass){
    return md5($pass);
}


//$testObj = new admin();
//$result = $testObj->loginUser('N.Amanquah', 'N.Amanquah');
//$row = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($row);
//
//if(count($row[0]) == 0){
//    echo 'invalid user';
//}else{
//    echo 'valid user';
//}

//$pass = $row[0]['password'];


//$testObj->addAdmin('N.Amanquah', 'N.Amanquah', 'Nathan', 'Amanquah');