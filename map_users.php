<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT id, name, email, phone, avatar
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$avatar = !empty($user['avatar'])
    ? $user['avatar']
    : 'images/avatar.png';

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NTTU - Bản đồ trường</title>

    <link rel="stylesheet" href="indexStyle.css">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >
</head>

<body>

<div class="page">

    <!-- ================= HEADER ================= -->

    <header class="header">

        <!-- Logo -->
        <div class="logo-area">
            <img src="images/logo.png" alt="NTTU Logo">
        </div>


        <div class="header-right">

            <!-- Search -->
            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    placeholder="Search for something"
                >

            </div>
        <!-- User Avatar -->
        <a href="account.php" class="user-avatar-link">
        <img src="<?= htmlspecialchars($avatar) ?>"
            alt="Avatar"
            style="
            width:40px;
            height:40px;
            border-radius:50%;
            object-fit:cover;
            cursor:pointer;">
        </a>
        </div>
    </header>


    <!-- ================= MAIN ================= -->

    <div class="main">


        <!-- ================= SIDEBAR ================= -->

        <aside class="sidebar">

            <nav class="menu">

                <!-- Home -->
                <a href="user.php" class="menu-item">

                    <i class="fa-solid fa-house"></i>

                    <span>Home</span>

                </a>


                <!-- Map -->
                <a href="map_users.php" class="menu-item active">

                    <i class="fa-solid fa-map"></i>

                    <span>Bản đồ trường</span>

                </a>


                <!-- Accounts -->
                <a href="account.php" class="menu-item">

                    <i class="fa-solid fa-user"></i>

                    <span>Accounts</span>

                </a>


                <!-- Subjects -->
                <a href="course_users.php" class="menu-item">

                    <i class="fa-solid fa-book-open"></i>

                    <span>Môn học</span>

                </a>


                <!-- Community -->
                <a href="community_users.php" class="menu-item">

                    <i class="fa-solid fa-users"></i>

                    <span>Cộng đồng</span>

                </a>


                <!-- Ranking -->
                <a href="ranking_user.php" class="menu-item">

                    <i class="fa-solid fa-ranking-star"></i>

                    <span>Xếp hạng</span>

                </a>


                <!-- Setting -->
                <a href="#" class="menu-item">

                    <i class="fa-solid fa-gear"></i>

                    <span>Setting</span>

                </a>

            </nav>

        </aside>


        <!-- ================= CONTENT ================= -->

  
<main class="content map-content">

    <!-- Bộ chọn cơ sở + tầng -->
    <div class="map-controls">

        <!-- Chọn cơ sở -->
        <div class="branch-select" id="branchSelect">

            <button class="branch-select-button" id="branchButton">
                <span id="selectedBranch">Cơ sở 1</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="branch-dropdown" id="branchDropdown">

                <div class="branch-option active"
                     data-branch="coso1">
                    Cơ sở 1
                </div>

                <div class="branch-option"
                     data-branch="coso3">
                    Cơ sở 3
                </div>

                <div class="branch-option"
                     data-branch="coso4">
                    Cơ sở 4
                </div>

            </div>

        </div>


        <!-- Chọn tầng -->
        <div class="floor-select" id="floorSelect">

            <button class="floor-select-button" id="floorButton">
                <span id="selectedFloor">Tầng trệt</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="floor-dropdown" id="floorDropdown">
                <!-- JS tự tạo -->
            </div>

        </div>

    </div>


    <!-- Bản đồ -->
    <div class="map-wrapper">

        <img
            src="images/map/CoSo1/TRET.webp"
            alt="Bản đồ Cơ sở 1 - Tầng trệt"
            class="campus-map"
            id="campusMap"
        >

    </div>

</main>

    </div>

</div>
    <script src="map.js"></script>
</body>

</html>
