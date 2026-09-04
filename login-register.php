<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /*Kiểm tra email*/
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $checkEmail = $stmt->get_result();

    if ($checkEmail->num_rows > 0) {

        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';
        $phone = '';
        $avatar = 'images/avatar.png';

        $insertStmt = $conn->prepare("
            INSERT INTO users
            (name, email, phone, avatar, password, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $insertStmt->bind_param(
            "ssssss",
            $name,
            $email,
            $phone,
            $avatar,
            $hashedPassword,
            $role
        );

        $insertStmt->execute();
    }

    header("Location: login.php");
    exit();
}


if (isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Lưu thông tin user vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['avatar'] = $user['avatar'] ?: 'images/avatar.png';

            if ($user['role'] === 'admin') {

                header("Location: admin.php");

            } else {

                header("Location: user.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';

    header("Location: login.php");
    exit();
}
?>
