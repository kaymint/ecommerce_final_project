<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/24/16
 * Time: 12:21 AM
 */

require_once 'adb_object.php';
require_once '../controller/security.php';

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
     * @param $secret
     * @param $key
     * @return bool
     */
    function authenticateUser($secret, $key){

        $result = $this->getUser($key);
        $row = $result->fetch_assoc();

        if(count($row) == 0){
            return false;
        }else{
            $enc_key = $row['password'];
            return verifyKey($secret, $enc_key);
        }
    }

    /**
     * @param $user
     * @return bool|mysqli_result
     */
    public function getUser($user){
        $str_query = "SELECT AU.username, AU.password
                      FROM admin AU
                      WHERE AU.username = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("s", $user);

        $stmt->execute();

        return $stmt->get_result();
    }

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