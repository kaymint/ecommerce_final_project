<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/11/16
 * Time: 10:47 PM
 */

require_once 'adb_object.php';

class order extends adb_object{

    /**
     * laptop constructor.
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     * @param $total
     * @param $address1
     * @param $address2
     * @param $phone
     * @param $email
     * @param $country
     * @param $firstname
     * @param $lastname
     * @return bool|mysqli_stmt
     */
    function addReceipt($total, $address1, $address2, $phone, $email, $country, $firstname, $lastname)
    {

        //sql query
        $str_query = "INSERT INTO order_receipts(total_cost, shipping_address1,
                              shipping_address2, phone, email, country, firstname, lastname)
                      VALUES (?, ?, ?,?, ?,?, ?, ?)";

        $stmt = $this->prepareQuery($str_query);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("dsssssss",  $total, $address1, $address2, $phone, $email, $country,
            $firstname, $lastname);


        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $recId
     * @param $lId
     * @param $cost
     * @return bool|mysqli_stmt
     */
    function addOrder($recId, $lId, $cost , $qty){

        //sql query
        $str_query = "INSERT INTO orders(receipt_id, laptop_id, cost, qty)
                      VALUES (?, ?, ?, ?)";

        $stmt = $this->prepareQuery($str_query);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("iidi", $recId, $lId, $cost, $qty);


        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $recId
     * @return bool|mysqli_stmt
     */
    function updateReceipt($recId){

        //sql query
        $str_query = "UPDATE order_receipts
                      SET
                      paid = ?,
                      date_paid = ?
                      WHERE receipt_id = ?";

        $stmt = $this->prepareQuery($str_query);

        $paid = 'PAID';
        $date = date("Y-m-d h:i:s");

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ssi", $paid, $date, $recId);


        $stmt->execute();

        return $stmt;
    }



    /**
     * @return bool|mysqli_result
     */
    function getNumOrders(){
        //sql query
        $str_query = "SELECT COUNT(*) AS numOrders FROM orders";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @return bool|mysqli_result
     */
    function getNumSales(){
        //sql query
        $str_query = "SELECT COUNT(*) AS numSales FROM order_receipts WHERE paid='PAID'";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @return bool|mysqli_result
     */
    function getOrders(){
        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      RO.receipt_id,
                      RO.shipping_address1,
                      CONCAT(RO.firstname, \" \",  RO.lastname) AS recepient,
                      RO.date_ordered,
                      O.qty,
                      O.cost,
                      O.order_id,
                      CASE
                      WHEN RO.date_paid IS NULL THEN 'processing'
                      ELSE 'delivered'
                      END as 'status'

                    FROM orders O
                      INNER JOIN order_receipts RO
                        ON RO.receipt_id = O.receipt_id
                      INNER JOIN laptop L
                        ON O.laptop_id = L.laptop_id
                      INNER JOIN brand B
                        ON L.brand_id = B.brand_id
                      INNER JOIN hard_drive H
                        ON L.hard_drive = H.drive_id
                      INNER JOIN ram R
                        ON L.ram = R.ram_id
                      INNER JOIN display D
                        ON L.display = D.display_id
                      INNER JOIN os OS
                        ON L.os = OS.os_id
                      INNER JOIN processor P
                        ON L.processor = P.processor_id
                      INNER JOIN inventory I
                        ON I.laptop_id = L.laptop_id
                    ORDER BY RO.date_ordered DESC";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @return bool|mysqli_result
     */
    function getOrdersByDate($date, $limit){
        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      RO.receipt_id,
                      RO.shipping_address1,
                      CONCAT(RO.firstname, \" \",  RO.lastname) AS recepient,
                      RO.date_ordered,
                      O.qty,
                      O.cost,
                      O.order_id,
                      CASE
                      WHEN RO.date_paid IS NULL THEN 'processing'
                      ELSE 'delivered'
                      END as 'status'

                    FROM orders O
                      INNER JOIN order_receipts RO
                        ON RO.receipt_id = O.receipt_id
                      INNER JOIN laptop L
                        ON O.laptop_id = L.laptop_id
                      INNER JOIN brand B
                        ON L.brand_id = B.brand_id
                      INNER JOIN hard_drive H
                        ON L.hard_drive = H.drive_id
                      INNER JOIN ram R
                        ON L.ram = R.ram_id
                      INNER JOIN display D
                        ON L.display = D.display_id
                      INNER JOIN os OS
                        ON L.os = OS.os_id
                      INNER JOIN processor P
                        ON L.processor = P.processor_id
                      INNER JOIN inventory I
                        ON I.laptop_id = L.laptop_id
                      WHERE DATE(RO.date_ordered) BETWEEN DATE(?) AND DATE(?)
                    ORDER BY RO.date_ordered DESC";

        $str_query .= " ".$limit;

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $daterange = explode(" - ", $date);
        $from = $daterange[0];
        $to = $daterange[1];

        $stmt->bind_param("ss", $from, $to);

        $stmt->execute();

        return $stmt->get_result();
    }


    function getCountOrdersByDate($date){
        //sql query
        $str_query = "SELECT
                      COUNT(*) AS totalCount
                      FROM order_receipts RO
                      WHERE DATE(RO.date_ordered) BETWEEN DATE(?) AND DATE(?)";


        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $daterange = explode(" - ", $date);
        $from = trim($daterange[0]);
        $to = trim($daterange[1]);

        $stmt->bind_param("ss", $from, $to);

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $limit
     * @return bool|mysqli_result
     */
    function getSales($limit){
        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      RO.receipt_id,
                      RO.shipping_address1,
                      CONCAT(RO.firstname, \" \",  RO.lastname) AS recepient,
                      RO.date_ordered,
                      O.qty,
                      O.cost,
                      O.order_id
                    FROM orders O
                      INNER JOIN order_receipts RO
                        ON RO.receipt_id = O.receipt_id
                      INNER JOIN laptop L
                        ON O.laptop_id = L.laptop_id
                      INNER JOIN brand B
                        ON L.brand_id = B.brand_id
                      INNER JOIN hard_drive H
                        ON L.hard_drive = H.drive_id
                      INNER JOIN ram R
                        ON L.ram = R.ram_id
                      INNER JOIN display D
                        ON L.display = D.display_id
                      INNER JOIN os OS
                        ON L.os = OS.os_id
                      INNER JOIN processor P
                        ON L.processor = P.processor_id
                      INNER JOIN inventory I
                        ON I.laptop_id = L.laptop_id
                      WHERE DATE(RO.date_paid) BETWEEN DATE(?) AND DATE(?)
                        AND RO.paid = 'PAID'
                    ORDER BY RO.date_ordered DESC";

        $str_query .= " ".$limit;

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $date
     * @return bool|mysqli_result
     */
    function getSalesByDate($date, $limit){
        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      RO.receipt_id,
                      RO.shipping_address1,
                      CONCAT(RO.firstname, \" \",  RO.lastname) AS recepient,
                      RO.date_ordered,
                      O.qty,
                      O.cost,
                      O.order_id
                    FROM orders O
                      INNER JOIN order_receipts RO
                        ON RO.receipt_id = O.receipt_id
                      INNER JOIN laptop L
                        ON O.laptop_id = L.laptop_id
                      INNER JOIN brand B
                        ON L.brand_id = B.brand_id
                      INNER JOIN hard_drive H
                        ON L.hard_drive = H.drive_id
                      INNER JOIN ram R
                        ON L.ram = R.ram_id
                      INNER JOIN display D
                        ON L.display = D.display_id
                      INNER JOIN os OS
                        ON L.os = OS.os_id
                      INNER JOIN processor P
                        ON L.processor = P.processor_id
                      INNER JOIN inventory I
                        ON I.laptop_id = L.laptop_id
                      WHERE DATE(RO.date_paid) BETWEEN DATE(?) AND DATE(?)
                        AND RO.paid = 'PAID'
                    ORDER BY RO.date_ordered DESC";

        $str_query .= " ".$limit;

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("s", $date);

        $stmt->execute();

        return $stmt->get_result();
    }

    function getSalesTotal($date){
        //sql query
        $str_query = "SELECT
                        SUM(RO.total_cost) AS totalSum
                        FROM order_receipts RO
                        WHERE DATE(RO.date_paid) BETWEEN DATE(?) AND DATE(?)
                        AND RO.paid = 'PAID'";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $daterange = explode(" - ", $date);
        $from = trim($daterange[0]);
        $to = trim($daterange[1]);

        $stmt->bind_param("ss", $from, $to);

        $stmt->execute();

        return $stmt->get_result();
    }


    function getOrdersTotal($date){
        //sql query
        $str_query = "SELECT
                        SUM(O.cost) AS totalSum
                        FROM orders O
                        INNER JOIN order_receipts RO
                        ON O.receipt_id = RO.receipt_id
                        WHERE DATE(RO.date_paid) BETWEEN DATE(?) AND DATE(?)";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $daterange = explode(" - ", $date);
        $from = trim($daterange[0]);
        $to = trim($daterange[1]);

        $stmt->bind_param("ss", $from, $to);

        $stmt->execute();

        return $stmt->get_result();
    }


    function getSalesCount($date){
        //sql query
        $str_query = "SELECT
                      COUNT(*) AS totalCount
                      FROM order_receipts RO
                      WHERE DATE(RO.date_paid) BETWEEN DATE(?) AND DATE(?)
                      AND RO.paid = 'PAID'";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $daterange = explode(" - ", $date);
        $from = trim($daterange[0]);
        $to = trim($daterange[1]);

        $stmt->bind_param("ss", $from, $to);

        $stmt->execute();

        return $stmt->get_result();
    }
}


//$testObj = new order();
//$limit = " LIMIT 0, 10";
//////$date = date("Y-m-d");
////
//$date = '2016-04-13';
////echo $date;
//$result = $testObj->getCountOrdersByDate($date, $limit);
////$row = $result->fetch_all(MYSQLI_ASSOC);
//$row = $result->fetch_assoc();
//var_dump($row);