<?php
session_start();
include("includes/db.php");
require("includes/fpdf/fpdf.php");

if (!isset($_GET['id'])) {
    die("No sale ID provided.");
}

$sale_id = intval($_GET['id']);

// Fetch sale
$sale = $conn->query("SELECT * FROM sales WHERE id=$sale_id")->fetch_assoc();

// Fetch sale items
$items = $conn->query("SELECT si.*, p.name 
                       FROM sale_items si 
                       JOIN products p ON si.product_id = p.id 
                       WHERE si.sale_id=$sale_id");

// Create PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Header
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Supermarket Receipt",0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Sale ID: ".$sale['id']." | Date: ".$sale['created_at'],0,1,'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,"Product",1);
$pdf->Cell(30,10,"Price",1);
$pdf->Cell(30,10,"Qty",1);
$pdf->Cell(40,10,"Subtotal",1);
$pdf->Ln();

// Table Data
$pdf->SetFont('Arial','',12);
$total = 0;
while ($row = $items->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;

    $pdf->Cell(80,10,$row['name'],1);
    $pdf->Cell(30,10,"$".number_format($row['price'],2),1);
    $pdf->Cell(30,10,$row['quantity'],1);
    $pdf->Cell(40,10,"$".number_format($subtotal,2),1);
    $pdf->Ln();
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(140,10,"Total",1);
$pdf->Cell(40,10,"$".number_format($total,2),1);
$pdf->Ln(20);

// Footer
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,10,"Thank you for shopping with us!",0,1,'C');

$pdf->Output();
?>
