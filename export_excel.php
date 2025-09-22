<?php
include("includes/db.php");


// Apply same filter logic
$where = "";
if (isset($_GET['filter'])) {
    if ($_GET['filter'] == "today") {
        $where = "WHERE DATE(s.created_at) = CURDATE()";
    } elseif ($_GET['filter'] == "week") {
        $where = "WHERE YEARWEEK(s.created_at, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($_GET['filter'] == "month") {
        $where = "WHERE MONTH(s.created_at) = MONTH(CURDATE()) AND YEAR(s.created_at) = YEAR(CURDATE())";
    } elseif ($_GET['filter'] == "custom" && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $start = $_GET['start_date'];
        $end = $_GET['end_date'];
        $where = "WHERE DATE(s.created_at) BETWEEN '$start' AND '$end'";
    }
}

$result = $conn->query("
    SELECT s.id, s.total, s.created_at, u.name AS cashier 
    FROM sales s 
    LEFT JOIN users u ON s.user_id = u.id 
    $where
    ORDER BY s.created_at DESC
");

// Headers for download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_report.xls");

// Output
echo "Sale ID\tCashier\tTotal\tDate\n";
while ($row = $result->fetch_assoc()) {
    echo $row['id']."\t".$row['cashier']."\t".$row['total']."\t".$row['created_at']."\n";
}
?>
