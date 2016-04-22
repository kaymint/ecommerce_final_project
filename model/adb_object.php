<?php
//error_reporting(E_ERROR | E_PARSE);
// A user-defined error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline) {
    $message = "\n Custom error: [$errno] $errstr ";
    $message .= "\n>> Error on line $errline in $errfile";
    error_log($message, 3, "../customer_view/error.log");
}

// Set user-defined error handler function
set_error_handler("myErrorHandler");
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 2/5/16
 * Time: 8:45 PM
 */

define("DB_HOST", 'localhost');

define("DB_NAME", 'e_commerce_final');
define("DB_PORT", 3306);
define("DB_USER","root");
define("DB_PWORD","");


class adb_object{

    var $link;
    var $result;
    var $mysqli;

    function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PWORD, DB_NAME);
    }

    function connect(){

        if(!isset($this->mysqli)){
            $this->__construct();
        }

        if($this->mysqli->connect_errno){

            trigger_error("Connection to database failed");

            printf("Connection failed %s\n", $this->mysqli->error);
            exit();
        }
    }


    function query($str_query){
        if(!isset($this->mysqli)){
            $this->connect();
        }

        $this->result = $this->mysqli->query($str_query);

        if($this->result){
            return true;
        }

        return false;
    }

    function prepareQuery($str_query){
        if(!isset($this->mysqli)){
            $this->connect();
        }

        $stmt = $this->mysqli->prepare($str_query);

        return $stmt;
    }


    function fetch(){

        //fetch data from query

        if(isset($this->result)){
            return $this->result->fetch_assoc();
        }

        return false;
    }


    function fetch_all(){

        //fetch data from query

        if(isset($this->result)){
            return $this->result->fetch_all(MYSQLI_ASSOC);
        }

        return false;
    }


    function get_num_rows(){

        return $this->mysqli->affected_rows;
    }


    function get_insert_id(){

        return $this->mysqli->insert_id;
    }

    function close_connection(){

        return $this->mysqli->close();
    }
}

$adb = new adb_object();




