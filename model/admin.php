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
        $firstname = twoWayEncrypt($firstname);
        $lastname = twoWayEncrypt($lastname);
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
     * @param $password
     * @param $username
     * @return array|bool
     */
    function authenticateUser($password, $username){

        $result = $this->getUser($username);
        $row = $result->fetch_assoc();

        if(count($row) == 0){
            return false;
        }else{
            $enc_key = $row['password'];
            $result=  verifyKey($password, $enc_key);
            if($result){
                return $row;
            }else{
                return false;
            }
        }
    }

    /**
     * @param $user
     * @return bool|mysqli_result
     */
    private function getUser($user){
        $str_query = "SELECT AU.username,
                      AU.password,
                      AU.admin_id,
                      AU.firstname,
                      AU.lastname
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

    /**
     * @param $user_id
     * @return bool|mysqli_result
     */
    public function getUserDetails($user_id){
        $str_query = "SELECT
                      AU.username,
                      AU.password,
                      AU.firstname,
                      AU.lastname
                      FROM admin AU
                      WHERE AU.admin_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        return $stmt->get_result();
    }

}


//$testObj = new admin();
//$result = $testObj->authenticateUser('Kenneth.Mensah', 'Kenneth.Mensah');
//var_dump($result);
//$lastname = twoWayDecrypt($result['lastname']);
//var_dump($lastname);
//$row = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($row);
//
//if(count($row[0]) == 0){
//    echo 'invalid user';
//}else{
//    echo 'valid user';
//}

//$pass = $row[0]['password'];
//$testObj->addAdmin('Kenneth.Mensah', 'Kenneth.Mensah', 'Kenneth', 'Mensah');