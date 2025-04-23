<?php
session_start();
require_once '../../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['user-name'] ?? 'name';
    $username = $_POST['username'] ?? 'username';
    $email = $_POST['user-email'] ?? 'email@gmail.com';
    $password = $_POST['user-password'] ?? 'password';
    $role = $_POST['user-role'] ?? 'customer';
    $response = ['success' => false, 'message' => ''];
    
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin người dùng!';
        echo json_encode($response);
        sleep(10);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Email không hợp lệ!';
        echo json_encode($response);
        exit;
    }
    
    $check_exist = $conn->prepare("SELECT id FROM nguoidung WHERE username = ? OR email = ?");
    $check_exist->bind_param("ss", $username, $email);
    $check_exist->execute();
    $result = $check_exist->get_result();
    
    if ($result->num_rows > 0) {
        $response['message'] = 'Username hoặc email đã tồn tại!';
        echo json_encode($response);
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO nguoidung (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $username, $email, $hashed_password, $role);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Thêm người dùng thành công!';
    } else {
        $response['message'] = 'Lỗi khi thêm người dùng: ' . $conn->error;
    }
    
    echo json_encode($response);
}