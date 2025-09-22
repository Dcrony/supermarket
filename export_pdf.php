<?php
require("includes/db.php");
require("includes/fpdf/fpdf.php");

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);

// Title
$pdf->Cell(0, 10, "Sales Report", 0, 1, "C");
$pdf->Ln(5);

// =====================
// Sales Transactions
// =====================
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(20, 10, "ID", 1);
$pdf->Cell(50, 10, "Cashier", 1);
$pdf->Cell(40, 10, "Total", 1);
$pdf->Cell(60, 10, "Date", 1);
$pdf->Ln();

$pdf->SetFont("Arial", "", 11);
$sales = $conn->query("
    SELECT s.id, s.total, s.created_at, u.username AS cashier 
    FROM sales s 
    LEFT JOIN users u ON s.cashier_id = u.id 
    ORDER BY s.created_at DESC
");

while ($row = $sales->fetch_assoc()) {
    $pdf->Cell(20, 10, $row['id'], 1);
    $pdf->Cell(50, 10, $row['cashier'] ?? "Unknown", 1);
    $pdf->Cell(40, 10, "$" . number_format($row['total'], 2), 1);
    $pdf->Cell(60, 10, $row['created_at'], 1);
    $pdf->Ln();
}

// =====================
// Best-Selling Products
// =====================
$pdf->Ln(10);
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(0, 10, "Best Selling Products", 0, 1, "C");
$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(90, 10, "Product", 1);
$pdf->Cell(40, 10, "Quantity Sold", 1);
$pdf->Ln();

$pdf->SetFont("Arial", "", 11);
$best_sellers = $conn->query("
    SELECT p.name, SUM(si.quantity) as total_sold 
    FROM sale_items si 
    JOIN products p ON si.product_id = p.id 
    GROUP BY si.product_id 
    ORDER BY total_sold DESC 
    LIMIT 5
");

while ($row = $best_sellers->fetch_assoc()) {
    $pdf->Cell(90, 10, $row['name'], 1);
    $pdf->Cell(40, 10, $row['total_sold'], 1);
    $pdf->Ln();
}

// Output PDF to browser
$pdf->Output("I", "sales_report.pdf");
?>
