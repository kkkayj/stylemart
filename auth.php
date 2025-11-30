<?php

require_once __DIR__ . '/config.php';

function ensure_logged_in() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function ensure_admin() {
    ensure_logged_in();
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        // Forbidden - redirect or show message
        header('HTTP/1.1 403 Forbidden');
        echo "403 Forbidden - You do not have permission to access this page.";
        exit;
    }
}
