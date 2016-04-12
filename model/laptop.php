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
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      CONCAT(D.size, '\" ', D.display_type) AS display,
                      OS.os_name AS os,
                      P.processor_name AS processor,
                      R.size AS memory,
                      CONCAT(H.size, H.size_metric) AS storage,
                      I.cost AS price,
                      I.onhand AS qty,
                      L.special_features AS special_features,
                      L.image
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
                    WHERE L.laptop_id = ?   ";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("i", $laptop_id);

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $limit
     * @return bool|mysqli_result
     */
    function viewAllLaptops($limit){

        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      CONCAT(D.size, '\" ', D.display_type) AS display,
                      OS.os_name AS os,
                      P.processor_name AS processor,
                      R.size AS memory,
                      CONCAT(H.size, H.size_metric) AS storage,
                      I.cost AS price,
                      I.onhand AS qty,
                      L.special_features AS special_features,
                      L.image
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
                        ON I.laptop_id = L.laptop_id ";

        $str_query .= " ".$limit;

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $limit
     * @param $brand_id
     * @return bool|mysqli_result
     */
    function viewLaptopsByBrand($limit, $brand_id){
        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      CONCAT(D.size, '\" ', D.display_type) AS display,
                      OS.os_name AS os,
                      P.processor_name AS processor,
                      R.size AS memory,
                      CONCAT(H.size, H.size_metric) AS storage,
                      I.cost AS price,
                      I.onhand AS qty,
                      L.special_features AS special_features,
                      L.image
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
                      WHERE B.brand_id = ?
                      ORDER BY I.date_added";

        $str_query .= " ".$limit;

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("i", $brand_id);

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @return bool|mysqli_result
     */
    function getStockCount(){
        //sql query
        $str_query = "SELECT COUNT(*) AS totalCount FROM laptop";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $brand_id
     * @return bool|mysqli_result
     */
    function getStockBrandCount($brand_id){
        //sql query
        $str_query = "SELECT COUNT(*) AS totalCount FROM
                      laptop WHERE brand_id = ?";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->bind_param("i", $brand_id);

        $stmt->execute();

        return $stmt->get_result();
    }



    /**
     * ==========Brands=======================
     */

    /**
     * @return bool|mysqli_result
     */
    function getBrands(){
        //sql query
        $str_query = "SELECT * FROM brands";

        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * ====================Search==========================
     */

    /**
     * @param $st
     * @return bool|mysqli_result
     */
    function getSimpleSearchCount($st){

        //sql query
        $str_query = "SELECT COUNT(*) AS totalCount
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
                      WHERE B.brand_name LIKE ? OR L.name LIKE ?
                      OR D.size LIKE ? OR R.size LIKE ? OR OS.os_name LIKE ? OR P.processor_name LIKE ?
                      ORDER BY L.name";


        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stname = "%{$st}%";

        $stmt->bind_param("ssssss", $stname, $stname, $stname, $stname, $stname, $stname);

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $st
     * @return bool|mysqli_result
     */
    function getSimpleSearch($st){

        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      CONCAT(D.size, '\" ', D.display_type) AS display,
                      OS.os_name AS os,
                      P.processor_name AS processor,
                      R.size AS memory,
                      CONCAT(H.size, H.size_metric) AS storage,
                      I.cost AS price,
                      I.onhand AS qty,
                      L.special_features AS special_features,
                      L.image
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
                      WHERE B.brand_name LIKE ? OR L.name LIKE ?
                      OR D.size LIKE ? OR R.size LIKE ? OR OS.os_name LIKE ? OR P.processor_name LIKE ?
                      ORDER BY L.name";


        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stname = "%{$st}%";

        $stmt->bind_param("ssssss", $stname, $stname, $stname, $stname, $stname, $stname);

        $stmt->execute();

        return $stmt->get_result();
    }


    /**
     * @param $stbrand
     * @param $stname
     * @return bool|mysqli_result
     */
    function getAdvancedSearchCount($stbrand, $stname){

        //sql query
        $str_query = "SELECT COUNT(*) AS totalCount
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
                      WHERE B.brand_name LIKE ? AND L.name LIKE ?";


        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stname = "%{$stname}%";
        $stbrand = "%{$stbrand}%";

        $stmt->bind_param("ss", $stname, $stbrand);

        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * @param $stbrand
     * @param $stname
     * @return bool|mysqli_result
     */
    function advancedSearch($stbrand, $stname){

        //sql query
        $str_query = "SELECT
                      CONCAT(B.brand_name, ' - ', L.name, ' ', D.size, '\" ', L.color ) AS product_name,
                      L.laptop_id,
                      CONCAT(D.size, '\" ', D.display_type) AS display,
                      OS.os_name AS os,
                      P.processor_name AS processor,
                      R.size AS memory,
                      CONCAT(H.size, H.size_metric) AS storage,
                      I.cost AS price,
                      I.onhand AS qty,
                      L.special_features AS special_features,
                      L.image
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
                      WHERE B.brand_name LIKE ? AND L.name LIKE ?";


        $stmt = $this->prepareQuery($str_query);

        if($stmt === false){
            return false;
        }

        $stname = "%{$stname}%";
        $stbrand = "%{$stbrand}%";

        $stmt->bind_param("ss", $stname, $stbrand);

        $stmt->execute();

        return $stmt->get_result();
    }


}