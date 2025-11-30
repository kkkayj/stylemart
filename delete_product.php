<?php
require_once 'auth.php';
ensure_admin();
require_once 'config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;
