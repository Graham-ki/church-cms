<?php
header('Content-Type: application/json');
include_once '../config/db.php';

$sql = "SELECT * FROM events ORDER BY start_date DESC";
$result = mysqli_query($conn, $sql);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);
?>
