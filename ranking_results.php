<?php

session_start();

require_once 'config.php';

/* =========================================================
   USER
========================================================= */

$userId = $_SESSION['user_id'] ?? null;

$avatar = 'images/avatar.png';

if ($userId) {

    $stmt = $conn->prepare("
        SELECT avatar
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $user =
        $stmt->get_result()
        ->fetch_assoc();


    if ($user && !empty($user['avatar'])) {

        $avatar =
            $user['avatar'];

    }
}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>NTTU - Tier List</title>


<link
    rel="stylesheet"
    href="indexStyle.css"
>


<link
    rel="stylesheet"
    href="ranking.css"
>


<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

</head>


<body>


<div class="page">


<!-- =====================================================
     HEADER
===================================================== -->

<header class="header">


<div class="logo-area">

    <img
        src="images/logo.png"
        alt="NTTU"
    >

</div>


<div class="header-right">


<?php if ($userId): ?>

<a
    href="account.php"
    class="user-avatar-link"
>

    <img
        src="<?= htmlspecialchars($avatar) ?>"
        alt="Avatar"
        style="
            width:40px;
            height:40px;
            border-radius:50%;
            object-fit:cover;
        "
    >

</a>

<?php else: ?>

<a
    href="login.php"
    class="header-link"
>

    <i class="fa-solid fa-right-to-bracket"></i>

    <span>Đăng nhập</span>

</a>

<?php endif; ?>


</div>

</header>


<div class="main">


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar">

<nav class="menu">


<a href="<?= $userId ? 'user.php' : 'index.html' ?>" class="menu-item">
    <i class="fa-solid fa-house"></i>
    <span>Home</span>

</a>


<a href="<?= $userId ? 'map_users.php' : 'map.html' ?>" class="menu-item">
    <i class="fa-solid fa-map"></i>
    <span>Bản đồ trường</span>
</a>


<a href="<?= $userId ? 'account.php' : 'login.php' ?>"class="menu-item">
    <i class="fa-solid fa-user"></i>
    <span>Accounts</span>
</a>


<a href="<?= $userId ? 'course_users.php' : 'course.html' ?>" class="menu-item">
    <i class="fa-solid fa-book-open"></i>
    <span>Môn học</span>
</a>


<a
    href="<?= $userId ? 'community_users.php' : 'community.php' ?>"
    class="menu-item"
>

    <i class="fa-solid fa-users"></i>

    <span>Cộng đồng</span>

</a>


<a
    href="ranking_user.php"
    class="menu-item active"
>

    <i class="fa-solid fa-ranking-star"></i>

    <span>Xếp hạng</span>

</a>


</nav>

</aside>


<!-- =====================================================
     CONTENT
===================================================== -->

<main class="ranking-content">


<div class="ranking-actions">


<a href="ranking_results.php">
    <button class="ranking-btn" type="button"> Kiểm tra kết quả</button>
</a>


<a href="<?= $userId ? 'ranking_user.php' : 'login.php' ?>">
    <button class="ranking-btn" type="button"> Bình chọn</button>
</a>


</div>


<h1 class="ranking-title">

    Tier List

</h1>


<?php if (!$userId): ?>

<div class="login-vote-message">

    Bạn đang xem kết quả chung.

    <br>

    Muốn tham gia bình chọn,
    <a href="login.php">hãy đăng nhập</a>.

</div>

<?php endif; ?>


<?php

include 'ranking_results_content.php';

?>


</main>


</div>

</div>

</body>

</html>