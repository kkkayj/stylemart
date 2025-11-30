<?php
header("Content-Type: text/plain");
$conn = new mysqli("localhost", "root", "", "stylemart");
if ($conn->connect_error) die("DB error");

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $conn->real_escape_string($data['order_id']);

$conn->query("UPDATE orders SET status='Paid' WHERE id='$order_id'");

echo "Payment successful!";
?>
