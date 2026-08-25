<?php
session_start();
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    <title>Login page</title>
</head>

<body>

    <div class="container <?= $activeForm === 'register' ? 'active' : '' ?>" id="container">
        <div class="form-container sign-up">
            <form action="login-register.php" method="POST">
                <h1>Tạo tài khoản </h1>
                <?= showError($errors['register']) ?>
                <span>Name</span>
                <input type="text" name="name" placeholder="Name" required>
                <span>Email</span>
                 <input type="email" name="email" placeholder="Email" required>
                <span>Password</span>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Tạo tài khoản</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="login-register.php" method="POST">
                <?= showError($errors['login']) ?>
                <h1>Đăng nhập</h1>
                <span>Email</span>
                <input type="email" name="email" placeholder="Email" required>
                <span>Password</span>
                <input type="password" name="password" placeholder="Password" required>
                  <button type="submit" name="login">Đăng nhập</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Xin Chào 36</h1>
                    <p>Bạn đã có tài khoản? Bấm vào nút này!</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Xin Chào 36</h1>
                    <p>Chưa có tài khoản? Bấm vào nút này!</p>
                    <button class="hidden" id="register">Đăng kí</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js.js"></script>
</body>

</html>
