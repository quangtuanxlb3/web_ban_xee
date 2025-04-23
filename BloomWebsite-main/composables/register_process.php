<?php
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT'] . "/BloomWebsite/config/connect.php";

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm-password'];

$errors = [];

if (empty($name) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
    $errors[] = "Vui lòng điền đầy đủ thông tin.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email không hợp lệ.";
}

if ($password !== $confirmPassword) {
    $errors[] = "Mật khẩu xác nhận không khớp.";
}

if (strlen($password) < 8) {
    $errors[] = "Mật khẩu phải ít nhất 8 ký tự.";
}

$stmt = $conn->prepare("SELECT id FROM nguoidung WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $errors[] = "Tên đăng nhập đã tồn tại.";
}
$stmt = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $errors[] = "Email đã được sử dụng.";
}
$stmt->close();

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    header("Location: /BloomWebsite/tai-khoan/register");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO nguoidung (name, email, username, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $username, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['register_success'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập.";
    header("Location: /BloomWebsite/tai-khoan/login");
} else {
    $_SESSION['register_errors'] = ["Lỗi khi đăng ký: " . $stmt->error];
    header("Location: /BloomWebsite/tai-khoan/register");
}

$stmt->close();
$conn->close();