<?php
session_start();
require_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && (($_SESSION['role'] ?? '') === 'admin');

$avatar = 'images/avatar.png';
if ($isLoggedIn) {
    $userId = (int)$_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT avatar FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $avatar = !empty($user['avatar']) ? $user['avatar'] : 'images/avatar.png';
    }
}

$result = $conn->query("
    SELECT id, name, category, icon, risk_level
    FROM courses
    ORDER BY id ASC
");

function riskClass($level) {
    return $level >= 4 ? 'risk-4' : ($level == 3 ? 'risk-3' : 'risk-1-2');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTTU - Môn học</title>
    <link rel="stylesheet" href="indexStyle.css">
    <link rel="stylesheet" href="course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="page">
    <header class="header">
        <div class="logo-area">
            <img src="images/logo.png" alt="NTTU Logo">
        </div>

        <div class="header-right">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search for something">
            </div>

            <?php if ($isLoggedIn): ?>
                <a href="<?= $isAdmin ? 'admin_settings.php' : 'account.php' ?>" class="user-avatar-link">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar"
                         style="width:40px;height:40px;border-radius:50%;object-fit:cover;cursor:pointer;">
                </a>
            <?php else: ?>
                <a href="login.php" class="header-link">
                    <i class="fa-solid fa-lock"></i>
                    <span>login</span>
                </a>
                <a href="login.php" class="header-link">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Sign up</span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="main">
        <aside class="sidebar">
            <nav class="menu">
                <a href="<?= $isAdmin ? 'admin.php' : ($isLoggedIn ? 'user.php' : 'index.html') ?>" class="menu-item">
                    <i class="fa-solid fa-house"></i><span>Home</span>
                </a>

                <?php if (!$isAdmin): ?>
                <a href="<?= $isLoggedIn ? 'map_users.php' : 'map.html' ?>" class="menu-item">
                    <i class="fa-solid fa-map"></i>
                    <span>Bản đồ trường</span>
                </a>
                <?php endif; ?>

                <a href="<?= $isAdmin ? 'admin_settings.php' : ($isLoggedIn ? 'account.php' : 'login.php') ?>" class="menu-item">
                    <i class="fa-solid fa-user"></i><span>Accounts</span>
                </a>

                <a href="courses.php" class="menu-item active">
                    <i class="fa-solid fa-book-open"></i><span>Môn học</span>
                </a>

                <a href="<?= $isAdmin ? 'community_admin.php' : ($isLoggedIn ? 'community_users.php' : 'community.php') ?>" class="menu-item">
                    <i class="fa-solid fa-users"></i><span>Cộng đồng</span>
                </a>

                <a href="<?= $isLoggedIn ? 'ranking_user.php' : 'ranking_results.php' ?>" class="menu-item">
                    <i class="fa-solid fa-ranking-star"></i><span>Xếp hạng</span>
                </a>
            </nav>
        </aside>

        <main class="course-content">
            <div class="course-header">
                <div>
                    <h1>Môn học</h1>
                    <p>Danh sách các môn học dành cho sinh viên NTTU</p>
                </div>

                <div class="course-header-actions">
                    <?php if ($isAdmin): ?>
                        <a href="course_form.php" class="admin-add-button">
                            <i class="fa-solid fa-plus"></i> Thêm môn học
                        </a>
                    <?php endif; ?>

                    <div class="course-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="courseSearch" placeholder="Tìm kiếm môn học...">
                    </div>
                </div>
            </div>

            <div class="course-grid" id="courseGrid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($course = $result->fetch_assoc()): ?>
                        <article class="course-card">
                            <div class="course-icon">
                                <i class="fa-solid <?= htmlspecialchars($course['icon']) ?>"></i>
                            </div>

                            <div class="course-info">
                                <h3><?= htmlspecialchars($course['name']) ?></h3>
                                <p><?= htmlspecialchars($course['category']) ?></p>

                                <div class="mini-risk <?= riskClass((int)$course['risk_level']) ?>">
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <span class="<?= $i <= (int)$course['risk_level'] ? 'active' : '' ?>"></span>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <a href="course.php?id=<?= (int)$course['id'] ?>" class="course-button">
                                Xem môn học <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <?php if ($isAdmin): ?>
                                <div class="admin-card-actions">
                                    <a href="course_form.php?id=<?= (int)$course['id'] ?>">
                                        <i class="fa-solid fa-pen"></i> Sửa
                                    </a>
                                    <form action="course_delete.php" method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa môn học này?');">
                                        <input type="hidden" name="id" value="<?= (int)$course['id'] ?>">
                                        <button type="submit">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="course-empty">
                        <i class="fa-solid fa-book-open"></i>
                        <p>Chưa có môn học nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<script src="course.js"></script>
</body>
</html>
