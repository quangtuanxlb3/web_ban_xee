<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $id = $_SESSION['user_id'];
    $role = $_SESSION['user_role'];

    if ($role === 'ADMIN') {
        header("Location: /BloomWebsite/admin");
        exit;
    }

    header("Location: /BloomWebsite/profile/$id");
    exit;
} else {
    header('Location: tai-khoan/login');
    exit;
}