<?php
session_start();
require_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && (($_SESSION['role'] ?? '') === 'admin');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: courses.php");
    exit();
}

/* Admin được cập nhật Risk Level ngay tại trang chi tiết */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_risk'])) {
    if (!$isAdmin) {
        http_response_code(403);
        exit('Bạn không có quyền thực hiện thao tác này.');
    }

    $riskLevel = (int)($_POST['risk_level'] ?? 1);
    if ($riskLevel < 1 || $riskLevel > 4) {
        $riskLevel = 1;
    }

    $stmt = $conn->prepare("UPDATE courses SET risk_level = ? WHERE id = ?");
    $stmt->bind_param("ii", $riskLevel, $id);
    $stmt->execute();

    header("Location: course.php?id=" . $id . "&risk_saved=1");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    http_response_code(404);
    exit('Không tìm thấy môn học.');
}

$avatar = 'images/avatar.png';
if ($isLoggedIn) {
    $userId = (int)$_SESSION['user_id'];
    $userStmt = $conn->prepare("SELECT avatar FROM users WHERE id = ? LIMIT 1");
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($user = $userResult->fetch_assoc()) {
        $avatar = !empty($user['avatar']) ? $user['avatar'] : 'images/avatar.png';
    }
}

function linesToList($text) {
    $lines = preg_split('/\r\n|\r|\n/', (string)$text);
    $lines = array_values(array_filter(array_map('trim', $lines), fn($line) => $line !== ''));
    return $lines;
}

function riskColorClass($level) {
    if ($level >= 4) return 'risk-level-4';
    if ($level == 3) return 'risk-level-3';
    return 'risk-level-1-2';
}

$riskLevel = (int)$course['risk_level'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['name']) ?> - NTTU</title>
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
                <a href="login.php" class="header-link"><i class="fa-solid fa-lock"></i><span>login</span></a>
                <a href="login.php" class="header-link"><i class="fa-solid fa-arrow-right-to-bracket"></i><span>Sign up</span></a>
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

        <main class="course-detail-content">
            <div class="detail-topbar">
                <a href="courses.php" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>

                <?php if ($isAdmin): ?>
                    <div class="detail-admin-actions">
                        <a href="course_form.php?id=<?= $id ?>" class="edit-button">
                            <i class="fa-solid fa-pen"></i> Chỉnh sửa
                        </a>
                        <form action="course_delete.php" method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn xóa môn học này?');">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button class="delete-button" type="submit">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <section class="course-detail-card">
                <div class="detail-title-row">
                    <div class="detail-course-icon">
                        <i class="fa-solid <?= htmlspecialchars($course['icon']) ?>"></i>
                    </div>
                    <div>
                        <h1><?= htmlspecialchars($course['name']) ?></h1>
                        <p><?= htmlspecialchars($course['category']) ?></p>
                    </div>
                </div>

                <div class="risk-section">
                    <div class="risk-label">Risk level:</div>

                    <?php if ($isAdmin): ?>
                        <form method="POST" class="risk-editor" id="riskForm">
                            <input type="hidden" name="risk_level" id="riskLevelInput" value="<?= $riskLevel ?>">
                            <div class="risk-bar editable-risk" id="riskBar" title="Bấm vào mức Risk để chỉnh sửa">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <button type="button"
                                            class="risk-segment <?= $i <= $riskLevel ? 'active ' . riskColorClass($riskLevel) : '' ?>"
                                            data-level="<?= $i ?>"
                                            aria-label="Risk level <?= $i ?>"></button>
                                <?php endfor; ?>
                            </div>
                            <span class="risk-value" id="riskValue">Mức <?= $riskLevel ?></span>
                            <button type="submit" name="save_risk" class="save-risk-button">
                                <i class="fa-solid fa-check"></i> Lưu
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="risk-bar">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <span class="risk-segment <?= $i <= $riskLevel ? 'active ' . riskColorClass($riskLevel) : '' ?>"></span>
                            <?php endfor; ?>
                        </div>
                        <span class="risk-value">Mức <?= $riskLevel ?></span>
                    <?php endif; ?>
                </div>

                <div class="detail-section">
                    <h2>Nội dung chính của môn học</h2>
                    <ul>
                        <?php foreach (linesToList($course['main_content']) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="detail-section">
                    <h2>Cách học hiệu quả</h2>
                    <ul>
                        <?php foreach (linesToList($course['learning_method']) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="detail-section">
                    <h2>Mục tiêu môn học</h2>
                    <ul>
                        <?php foreach (linesToList($course['objectives']) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

            <section class="course-detail-card resource-card">
                <div class="detail-section">
                    <h2>Giáo trình và Đề cương lý thuyết</h2>
                    <ul>
                        <?php foreach (linesToList($course['textbook']) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                        <?php foreach (linesToList($course['references_text']) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="detail-section">
                    <h2>Video bài giảng và Thực tế số</h2>
                    <ul>
                        <li>
                            Cơ sở dữ liệu video thực tế:
                            <?php if (!empty($course['video_url'])): ?>
                                <a href="<?= htmlspecialchars($course['video_url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <?= htmlspecialchars($course['video_url']) ?>
                                </a>
                            <?php else: ?>
                                Chưa cập nhật
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</div>

<?php if ($isAdmin): ?>
<script>
const riskBar = document.getElementById('riskBar');
const riskInput = document.getElementById('riskLevelInput');
const riskValue = document.getElementById('riskValue');

if (riskBar) {
    const segments = [...riskBar.querySelectorAll('.risk-segment')];

    function colorClass(level) {
        if (level === 4) return 'risk-level-4';
        if (level === 3) return 'risk-level-3';
        return 'risk-level-1-2';
    }

    function updateRisk(level) {
        riskInput.value = level;
        riskValue.textContent = 'Mức ' + level;

        segments.forEach((segment, index) => {
            segment.classList.remove('active', 'risk-level-1-2', 'risk-level-3', 'risk-level-4');
            if (index + 1 <= level) {
                segment.classList.add('active', colorClass(level));
            }
        });
    }

    segments.forEach(segment => {
        segment.addEventListener('click', () => {
            updateRisk(Number(segment.dataset.level));
        });
    });
}
</script>
<?php endif; ?>
</body>
</html>
