<?php

/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 4/11/16
 * Time: 10:48 PM
 */

require_once 'adb_object.php';

class laptop extends adb_object
{

    /**
     * laptop constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * ===============================================
     * Insertion Queries
     * ===============================================
     */

    /**
     * @param $brand_id
     * @param $display
     * @param $hard_drive
     * @param $processor
     * @param $ram
     * @param $os
     * @param $name
     * @param $color
     * @param $special_features
     * @param $img
     * @return bool|mysqli_stmt
     */
    function addLaptop($brand_id, $display, $hard_drive, $processor, $ram, $os, $name, $color, $special_features,
            $img){

        //sql query
        $str_query = "INSERT INTO laptop(brand_id, display, hard_drive, processor, ram, os, name, color,
                        special_features, image)
                      VALUES (?, ?, ?, ?,?, ?, ?, ?, ?, ?)";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("iiiiiissss", $brand_id,$display,$hard_drive, $processor, $ram, $os, $name, $color,
            $special_features, $img);


        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $laptop_id
     * @param $onhand
     * @param $cost
     * @return bool|mysqli_stmt
     */
    function addInventoryDetails($laptop_id, $onhand, $cost){

        //sql query
        $str_query = "INSERT INTO inventory(laptop_id, onhand, cost)
                      VALUES (?, ?, ?)";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("iid", $laptop_id, $onhand, $cost);


        $stmt->execute();

        return $stmt;
    }


    /**
     * ===============================================
     * Update Queries
     * ===============================================
     */

    /**
     * @param $brand_id
     * @param $display
     * @param $hard_drive
     * @param $processor
     * @param $ram
     * @param $os
     * @param $name
     * @param $color
     * @param $special_features
     * @param $image
     * @param $laptop_id
     * @return bool|mysqli_stmt
     */
    function updateLaptop($brand_id, $display, $hard_drive, $processor, $ram, $os, $name, $color,
                             $special_features, $image, $laptop_id){
        //sql query
        $str_query = "UPDATE laptop
                        SET brand_id =  ?,
                        display = ?,
                        hard_drive = ?,
                        processor = ?,
                        ram = ?,
                        os = ?,
                        name = ? ,
                        color = ?,
                        special_features = ?,
                        image = ?
                      WHERE laptop_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("iiiiiissssi", $brand_id, $display, $hard_drive, $processor, $ram, $os, $name,
            $color, $special_features, $image, $laptop_id);

        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $laptop_id
     * @param $onhand
     * @param $cost
     * @return bool|mysqli_stmt
     */
    function updateInventory($laptop_id, $onhand, $cost){
        //sql query
        $str_query = "UPDATE
                      inventory
                      SET
                      onhand = ?,
                      cost = ?
                      WHERE laptop_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("iid", $laptop_id, $onhand, $cost);


        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $cost
     * @return bool|mysqli_stmt
     */
    function updateCost($laptop_id, $cost){
        //sql query
        $str_query = "UPDATE inventory
                      SET
                      cost = ?
                      WHERE laptop_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("di", $cost, $laptop_id);


        $stmt->execute();

        return $stmt;
    }


    /**
     * @param $laptop_id
     * @param $qty
     * @return bool|mysqli_stmt
     */
    function updateQuantity($laptop_id, $qty){

        //sql query
        $str_query = "UPDATE inventory
                      SET
                      onhand = ?
                      WHERE laptop_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("ii", $qty, $laptop_id);


        $stmt->execute();

        return $stmt;
    }


    /**
     * ===============================================
     * Select Queries
     * ===============================================
     */

    /**
     * @param $laptop_id
     * @return bool|mysqli_result
     */
    function getLaptop($laptop_id){
        //sql query
        $str_query = "SELECT

                      FROM laptop L
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
                      WHERE L.laptop_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("i", $laptop_id);

        $stmt->execute();

        return $stmt->get_result();
    }


    function viewLaptopsByBrand($brand_id){

    }


}