<?php
session_start();
require_once 'config.php';

// Nếu chưa đăng nhập thì quay về login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Lấy thông tin user
$stmt = $conn->prepare("
    SELECT id, name, email, phone, avatar
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$avatar = !empty($user['avatar'])
    ? $user['avatar']
    : 'images/avatar.png';

$message = $_SESSION['account_message'] ?? '';
unset($_SESSION['account_message']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>NTTU - Tài khoản</title>
    <link rel="stylesheet" href="indexStyle.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
 
    <style>
        .account-content {
            flex: 1;
            background: #f5f6f8;
            min-height: calc(100vh - 70px);
            padding: 40px 0;
        }

        .account-box {
            width: 70%;
            max-width: 700px;
            margin: 0 auto;
        }

        .avatar-area {
            text-align: left;
            margin-bottom: 15px;
        }

        .account-avatar {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin-bottom: 5px;
        }

        .change-avatar {
            font-size: 11px;
            color: #555;
            cursor: pointer;
        }

        .account-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            height: 35px;
            padding: 0 10px;
            border: 1px solid #777;
            border-radius: 2px;
            background: #eee;
            outline: none;
        }

        .form-group input:focus {
            background: white;
            border-color: #2948a5;
        }

        .save-button {
            margin-top: 15px;
            width: 115px;
            height: 38px;
            border: none;
            border-radius: 2px;
            background: #2948a5;
            color: white;
            cursor: pointer;
        }

        .save-button:hover {
            background: #203b8b;
        }

        .message {
            color: green;
            margin-bottom: 15px;
            font-size: 13px;
        }

    </style>
</head>
<body>

<div class="page">

    <!-- HEADER -->
    <header class="header">

        <div class="logo-area">
            <img src="images/logo.png" alt="NTTU Logo">
        </div>

        <div class="header-right">

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    placeholder="Search for something"
                >

            </div>

            <!-- Avatar user -->
            <a href="account.php" class="user-avatar-link">

                <img
                    src="<?= htmlspecialchars($avatar) ?>"
                    alt="Avatar"
                    style="
                        width:38px;
                        height:38px;
                        border-radius:50%;
                        object-fit:cover;
                    "
                >

            </a>

        </div>

    </header>


    <!-- MAIN -->
    <div class="main">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <nav class="menu">

                <a href="user.php" class="menu-item">

                    <i class="fa-solid fa-house"></i>

                    <span>Home</span>

                </a>

                <a href="map_users.php" class="menu-item">

                    <i class="fa-solid fa-map"></i>

                    <span>Bản đồ trường</span>

                </a>

                <a href="account.php"
                   class="menu-item active">

                    <i class="fa-solid fa-user"></i>

                    <span>Accounts</span>

                </a>


                <a href="course_users.php" class="menu-item">

                    <i class="fa-solid fa-book-open"></i>

                    <span>Môn học</span>

                </a>


                <a href="community.html" class="menu-item">

                    <i class="fa-solid fa-users"></i>

                    <span>Cộng đồng</span>

                </a>


                <a href="#" class="menu-item">

                    <i class="fa-solid fa-ranking-star"></i>

                    <span>Xếp hạng</span>

                </a>


                <a href="#" class="menu-item">

                    <i class="fa-solid fa-gear"></i>

                    <span>Setting</span>

                </a>

            </nav>

        </aside>


        <!-- ACCOUNT CONTENT -->
        <main class="account-content">

            <div class="account-box">

                <?php if ($message): ?>

                    <div class="message">
                        <?= htmlspecialchars($message) ?>
                    </div>

                <?php endif; ?>


                <!-- Avatar -->
                <div class="avatar-area">

                    <img
                        src="<?= htmlspecialchars($avatar) ?>"
                        class="account-avatar"
                        alt="Avatar"
                    >

                </div>


                <!-- Form -->
                <form
                    class="account-form"
                    action="update_account.php"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <!-- Change avatar -->
                    <div class="form-group">

                        <label>Ảnh đại diện</label>

                        <input
                            type="file"
                            name="avatar"
                            accept="image/jpeg,image/png,image/webp"
                        >

                    </div>


                    <!-- Username -->
                    <div class="form-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($user['name']) ?>"
                            required
                        >

                    </div>


                    <!-- Email -->
                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                        >

                    </div>


                    <!-- Phone -->
                    <div class="form-group">

                        <label>Số điện thoại</label>

                        <input
                            type="text"
                            name="phone"
                            value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        >

                    </div>


                    <button
                        type="submit"
                        class="save-button"
                    >
                        Lưu thay đổi
                    </button>

                </form>

            </div>

        </main>

    </div>

</div>

</body>

</html>
