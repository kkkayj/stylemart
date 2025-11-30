<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


define('DB_HOST', 'sql100.infinityfree.com');
define('DB_NAME', 'if0_40508624_stylemart');  // Correct DB name
define('DB_USER', 'if0_40508624');
define('DB_PASS', 'ITOge264Ri');

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // In production, log error instead of showing it
    exit('Database connection failed: ' . $e->getMessage());
}
?>
