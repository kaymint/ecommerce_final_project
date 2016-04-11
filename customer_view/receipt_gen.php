<?php
/**
 * Created by PhpStorm.
 * User: StreetHustling
 * Date: 3/15/16
 * Time: 5:24 PM
 */

require_once 'fpdf.php';

session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Ln();

$company = "Company Name: ".$_SESSION['com_name'];
$email = "Email: ".$_SESSION['rec_email'];
$firstname = "First Name: ".$_SESSION['rec_firstname'];
$lastname = "Last Name: ".$_SESSION['rec_lastname'];
$phone = "Phone: ".$_SESSION['rec_phone'];
$address1 = "Address 1: ".$_SESSION['rec_address1'];
$address2 = "Address 2: ".$_SESSION['rec_address2'];
$country = "Country: ".$_SESSION['rec_country'];
$receipt_id = "Receipt No: ".$_SESSION['receipt_id'];
$overallTotal = "Overall: ".$_SESSION['overallTotal'];
$sub_total = "Subtotal: ".$_SESSION['sub_total'];
$discount = "Discount: ".$_SESSION['discount']. " %";

$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('Arial', 'b', 14);
$pdf->Cell(80, 20, 'Invoice:');
$pdf->Ln();
$pdf->SetFont('Arial', 'b', 11);
$pdf->Cell(80, 5, 'Billed To:');
$pdf->SetFont('Arial', 'i', 10);
$pdf->Ln();
$pdf->Cell(80, 5, $company);
$pdf->Ln();
$pdf->Cell(80, 5, $email);
$pdf->Ln();
$pdf->Cell(80, 5, $firstname);
$pdf->Ln();
$pdf->Cell(80, 5, $phone);
$pdf->Ln();
$pdf->Cell(80, 5, $address1);
$pdf->Ln();
$pdf->Cell(80, 5, $address2);
$pdf->Ln();
$pdf->Cell(80, 5, $country);
$pdf->Ln();
$pdf->Cell(80, 5, $receipt_id);



$pdf->Ln();
$pdf->Image('images/logo.png', 150, 10, 50);
$pdf->Ln();

$data = $_SESSION['cart_details'];

$pdf->SetFont('Arial', 'b', 11);
$headings = array("Item", "Unit Price", "Quantity", "Total");

foreach($headings as $key=>$col){
    if($key == 0){
        $pdf->Cell(80, 7, $col, 1);
    }else{
        $pdf->Cell(30, 7, $col, 1);
    }
}
$pdf->Ln();


$pdf->SetFont('Times', 'i', 10);
foreach ($data as $row) {
    $name = $row['brand_name'] ." ".$row['name']." ".$row['furniture_type'];
    $pdf->Cell(80, 6, $name , 1, 'L');
    $pdf->Cell(30, 6, $row['cost'] , 1, 'L');
    $pdf->Cell(30, 6, $row['count'] , 1, 'L');
    $pdf->Cell(30, 6, $row['itemTotal'] , 1, 'L');
    $pdf->Ln();
}

$pdf->Ln();
$pdf->Cell(80, 5, $sub_total, 1, 'R');
$pdf->Ln();
$pdf->Cell(80, 5, $discount, 1, 'R');
$pdf->Ln();
$pdf->SetFont('Arial', 'bi', 11);
$pdf->Cell(80, 5, $overallTotal, 1, 'R');

$pdf->Ln();

$pdf->Output();