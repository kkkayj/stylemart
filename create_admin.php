<?php

require 'config.php';

$username = 'jeff';  // <-- set a proper name
$email = 'jeffchew624@gmail.com';
$password_plain = 'Pikachu321123';
$role = 'admin';


$hash = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
try {
    $stmt->execute([$username, $email, $hash, $role]);
    echo "Admin created. Username: $username, Password: $password_plain\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
