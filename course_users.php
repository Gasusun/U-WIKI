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

    <title>NTTU - Môn học</title>

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
                <a href="map_users.php" class="menu-item">

                    <i class="fa-solid fa-map"></i>

                    <span>Bản đồ trường</span>

                </a>


                <!-- Accounts -->
                <a href="account.php" class="menu-item">

                    <i class="fa-solid fa-user"></i>

                    <span>Accounts</span>

                </a>


                <!-- Subjects -->
                <a href="course_users.php" class="menu-item active">

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

        <!-- ================= COURSE CONTENT ================= -->

        <main class="course-content">

            <!-- Tiêu đề -->
            <div class="course-header">
                <div>
                    <h1>Môn học</h1>
                    <p>Danh sách các môn học dành cho sinh viên NTTU</p>
                </div>

            <div class="course-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    id="courseSearch"
                    placeholder="Tìm kiếm môn học..."
                >
                </div>
            </div>


            <!-- Danh sách môn học -->
            <div class="course-grid" id="courseGrid">

                <!-- 1 -->
                <div class="course-card">
                    <div class="course-icon">
                    <i class="fa-solid fa-building"></i>
                </div>

                <div class="course-info">
                    <h3>Dịch vụ Hành chính</h3>
                    <p>Hành chính</p>
                </div>

                <a href="#" class="course-button">
                    Xem môn học
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>


                <!-- 2 -->
                <div class="course-card">
                    <div class="course-icon">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>

                <div class="course-info">
                    <h3>Công nghệ thông tin</h3>
                    <p>Công nghệ</p>
                </div>

                <a href="#" class="course-button">
                    Xem môn học
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>


        <!-- 3 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-language"></i>
            </div>

            <div class="course-info">
                <h3>Tiếng Anh</h3>
                <p>Ngoại ngữ</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 4 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-brain"></i>
            </div>

            <div class="course-info">
                <h3>Kỹ năng mềm trong kỷ nguyên số - Cơ bản</h3>
                <p>Kỹ năng mềm</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 5 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-calculator"></i>
            </div>

            <div class="course-info">
                <h3>Toán cao cấp</h3>
                <p>Toán học</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 6 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-code"></i>
            </div>

            <div class="course-info">
                <h3>Lập trình</h3>
                <p>Công nghệ thông tin</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 7 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-globe"></i>
            </div>

            <div class="course-info">
                <h3>Năng lực số và khai thác tài nguyên giáo dục mở</h3>
                <p>Kỹ năng số</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 8 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-puzzle-piece"></i>
            </div>

            <div class="course-info">
                <h3>Toán rời rạc</h3>
                <p>Toán học</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 9 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>

            <div class="course-info">
                <h3>Pháp luật đại cương</h3>
                <p>Pháp luật</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 10 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-terminal"></i>
            </div>

            <div class="course-info">
                <h3>Lập trình nâng cao</h3>
                <p>Công nghệ thông tin</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 11 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-database"></i>
            </div>

            <div class="course-info">
                <h3>Thiết kế và phát triển cơ sở dữ liệu</h3>
                <p>Cơ sở dữ liệu</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>


        <!-- 12 -->
        <div class="course-card">
            <div class="course-icon">
                <i class="fa-solid fa-book"></i>
            </div>

            <div class="course-info">
                <h3>Triết học Mác-Lê Nin</h3>
                <p>Triết học</p>
            </div>

            <a href="#" class="course-button">
                Xem môn học
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>

</main>
        

    </div>

</div>
    <script src="course.js"></script>
</body>

</html>
