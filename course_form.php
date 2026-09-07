<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: courses.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$isEdit = (bool)$id;

$course = [
    'name' => '',
    'category' => '',
    'icon' => 'fa-book',
    'risk_level' => 1,
    'main_content' => '',
    'learning_method' => '',
    'objectives' => '',
    'textbook' => '',
    'references_text' => '',
    'video_url' => ''
];

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();

    if (!$existing) {
        header("Location: courses.php");
        exit();
    }
    $course = array_merge($course, $existing);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-book');
    $riskLevel = (int)($_POST['risk_level'] ?? 1);
    $mainContent = trim($_POST['main_content'] ?? '');
    $learningMethod = trim($_POST['learning_method'] ?? '');
    $objectives = trim($_POST['objectives'] ?? '');
    $textbook = trim($_POST['textbook'] ?? '');
    $referencesText = trim($_POST['references_text'] ?? '');
    $videoUrl = trim($_POST['video_url'] ?? '');

    if ($name === '') $errors[] = 'Vui lòng nhập tên môn học.';
    if ($category === '') $errors[] = 'Vui lòng nhập danh mục.';
    if ($riskLevel < 1 || $riskLevel > 4) $errors[] = 'Risk level phải từ 1 đến 4.';

    if (!$errors) {
        if ($isEdit) {
            $stmt = $conn->prepare("
                UPDATE courses SET
                    name = ?, category = ?, icon = ?, risk_level = ?,
                    main_content = ?, learning_method = ?, objectives = ?,
                    textbook = ?, references_text = ?, video_url = ?
                WHERE id = ?
            ");
            $stmt->bind_param(
                "sssissssssi",
                $name, $category, $icon, $riskLevel,
                $mainContent, $learningMethod, $objectives,
                $textbook, $referencesText, $videoUrl, $id
            );
        } else {
            $stmt = $conn->prepare("
                INSERT INTO courses
                (name, category, icon, risk_level, main_content, learning_method,
                 objectives, textbook, references_text, video_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "sssissssss",
                $name, $category, $icon, $riskLevel,
                $mainContent, $learningMethod, $objectives,
                $textbook, $referencesText, $videoUrl
            );
        }

        if ($stmt->execute()) {
            $savedId = $isEdit ? $id : $conn->insert_id;
            header("Location: course.php?id=" . $savedId);
            exit();
        }

        $errors[] = 'Không thể lưu dữ liệu: ' . $conn->error;
    }

    $course = array_merge($course, [
        'name' => $name,
        'category' => $category,
        'icon' => $icon,
        'risk_level' => $riskLevel,
        'main_content' => $mainContent,
        'learning_method' => $learningMethod,
        'objectives' => $objectives,
        'textbook' => $textbook,
        'references_text' => $referencesText,
        'video_url' => $videoUrl
    ]);
}

$avatar = 'images/avatar.png';
$userId = (int)$_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT avatar FROM users WHERE id = ? LIMIT 1");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($user = $userResult->fetch_assoc()) {
    $avatar = !empty($user['avatar']) ? $user['avatar'] : 'images/avatar.png';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Chỉnh sửa môn học' : 'Thêm môn học' ?> - NTTU</title>
    <link rel="stylesheet" href="indexStyle.css">
    <link rel="stylesheet" href="course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="page">
    <header class="header">
        <div class="logo-area"><img src="images/logo.png" alt="NTTU Logo"></div>
        <div class="header-right">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search for something">
            </div>
            <a href="admin_settings.php" class="user-avatar-link">
                <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar"
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;cursor:pointer;">
            </a>
        </div>
    </header>

    <div class="main">
        <aside class="sidebar">
            <nav class="menu">
                <a href="admin.php" class="menu-item"><i class="fa-solid fa-house"></i><span>Home</span></a>
                <a href="admin_settings.php" class="menu-item"><i class="fa-solid fa-user"></i><span>Accounts</span></a>
                <a href="courses.php" class="menu-item active"><i class="fa-solid fa-book-open"></i><span>Môn học</span></a>
                <a href="community_admin.php" class="menu-item"><i class="fa-solid fa-users"></i><span>Cộng đồng</span></a>
                <a href="ranking_results.php" class="menu-item"><i class="fa-solid fa-ranking-star"></i><span>Xếp hạng</span></a>
            </nav>
        </aside>

        <main class="course-content">
            <div class="form-topbar">
                <a href="courses.php" class="back-button"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <h1><?= $isEdit ? 'Chỉnh sửa môn học' : 'Thêm môn học' ?></h1>
            </div>

            <?php if ($errors): ?>
                <div class="form-errors">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="course-form">
                <div class="form-grid">
                    <label>
                        Tên môn học *
                        <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" required>
                    </label>

                    <label>
                        Danh mục *
                        <input type="text" name="category" value="<?= htmlspecialchars($course['category']) ?>" required>
                    </label>

                    <label>
                        Icon Font Awesome
                        <input type="text" name="icon" value="<?= htmlspecialchars($course['icon']) ?>"
                               placeholder="Ví dụ: fa-book">
                    </label>

                    <div class="form-field">
                        <span>Risk level *</span>
                        <input type="hidden" name="risk_level" id="formRisk" value="<?= (int)$course['risk_level'] ?>">
                        <div class="risk-bar editable-risk form-risk" id="formRiskBar">
                            <?php for ($i=1; $i<=4; $i++): ?>
                                <button type="button" class="risk-segment <?= $i <= (int)$course['risk_level'] ? 'active' : '' ?>"
                                        data-level="<?= $i ?>"></button>
                            <?php endfor; ?>
                        </div>
                        <small id="formRiskText">Mức <?= (int)$course['risk_level'] ?></small>
                    </div>
                </div>

                <label>
                    Nội dung chính của môn học
                    <textarea name="main_content" rows="7"
                              placeholder="Mỗi ý viết trên một dòng."><?= htmlspecialchars($course['main_content']) ?></textarea>
                </label>

                <label>
                    Cách học hiệu quả
                    <textarea name="learning_method" rows="6"
                              placeholder="Mỗi ý viết trên một dòng."><?= htmlspecialchars($course['learning_method']) ?></textarea>
                </label>

                <label>
                    Mục tiêu môn học
                    <textarea name="objectives" rows="6"
                              placeholder="Mỗi ý viết trên một dòng."><?= htmlspecialchars($course['objectives']) ?></textarea>
                </label>

                <label>
                    Giáo trình
                    <textarea name="textbook" rows="4"
                              placeholder="Mỗi ý viết trên một dòng."><?= htmlspecialchars($course['textbook']) ?></textarea>
                </label>

                <label>
                    Tài liệu tham khảo
                    <textarea name="references_text" rows="4"
                              placeholder="Mỗi ý viết trên một dòng."><?= htmlspecialchars($course['references_text']) ?></textarea>
                </label>

                <label>
                    Link video
                    <input type="url" name="video_url" value="<?= htmlspecialchars($course['video_url']) ?>"
                           placeholder="https://www.youtube.com/...">
                </label>

                <div class="form-actions">
                    <a href="courses.php" class="cancel-button">Hủy</a>
                    <button type="submit" class="submit-button">
                        <i class="fa-solid fa-save"></i>
                        <?= $isEdit ? 'Lưu thay đổi' : 'Thêm môn học' ?>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
const formRiskBar = document.getElementById('formRiskBar');
const formRisk = document.getElementById('formRisk');
const formRiskText = document.getElementById('formRiskText');

function formRiskColor(level) {
    if (level === 4) return 'risk-level-4';
    if (level === 3) return 'risk-level-3';
    return 'risk-level-1-2';
}

function setFormRisk(level) {
    formRisk.value = level;
    formRiskText.textContent = 'Mức ' + level;

    formRiskBar.querySelectorAll('.risk-segment').forEach((segment, index) => {
        segment.classList.remove('active', 'risk-level-1-2', 'risk-level-3', 'risk-level-4');
        if (index + 1 <= level) {
            segment.classList.add('active', formRiskColor(level));
        }
    });
}

formRiskBar.querySelectorAll('.risk-segment').forEach(segment => {
    segment.addEventListener('click', () => setFormRisk(Number(segment.dataset.level)));
});

setFormRisk(Number(formRisk.value));
</script>
</body>
</html>
