<?php

session_start();

require_once 'config.php';


/*
|--------------------------------------------------------------------------
| KIỂM TRA ADMIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();
}


if (($_SESSION['role'] ?? '') !== 'admin') {

    header("Location: user.php");

    exit();
}


$currentAdminId =
    (int)$_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| LẤY TẤT CẢ TÀI KHOẢN
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        avatar,
        role
    FROM users
    ORDER BY id ASC
");


$stmt->execute();


$result =
    $stmt->get_result();


$users = [];


while (
    $row =
    $result->fetch_assoc()
) {

    $users[] = $row;
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

<title>Quản lý tài khoản</title>

<link
    rel="stylesheet"
    href="indexStyle.css"
>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>

.settings-content {
    flex: 1;
    background: #f5f6f8;
    padding: 30px;
    overflow-y: auto;
}

.settings-box {
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 25px;
}

.settings-title {
    margin-top: 0;
}

.account-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
}

.account-table th,
.account-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.account-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.role-admin {
    color: #d32f2f;
    font-weight: bold;
}

.role-user {
    color: #3564ff;
    font-weight: bold;
}

.action-btn {
    display: inline-block;
    border: none;
    padding: 7px 11px;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 12px;
}

.edit-btn {
    background: #3564ff;
    color: white;
}

.delete-btn {
    background: #e53935;
    color: white;
}

.self-label {
    color: #777;
    font-size: 12px;
}

</style>

</head>

<body>

<div class="page">


<header class="header">

<div class="logo-area">

<img src="images/logo.png" alt="NTTU">
</div>
</header>


<div class="main">


<aside class="sidebar">

<nav class="menu">


<a href="admin.php" class="menu-item">
<i class="fa-solid fa-house"></i>
<span>Home</span>
</a>

<a href="admin_settings.php" class="menu-item active">
<i class="fa-solid fa-user"></i>
<span>Accounts</span>
</a>

<a href="course.html" class="menu-item">
<i class="fa-solid fa-book-open"></i>
<span>Môn học</span>
</a>


<a href="community_admin.php" class="menu-item">
<i class="fa-solid fa-users"></i>
<span>Cộng đồng</span>
</a>


<a href="ranking_results.php" class="menu-item">
<i class="fa-solid fa-ranking-star"></i>
<span>Xếp hạng</span>
</a>



</nav>

</aside>


<main class="settings-content">


<div class="settings-box">


<h1 class="settings-title">

<i class="fa-solid fa-users-gear"></i>

Quản lý tài khoản

</h1>


<p>

Tổng số tài khoản:

<strong>

<?= count($users) ?>

</strong>

</p>


<table class="account-table">

<thead>

<tr>
<th>ID</th>

<th>Avatar</th>

<th>Tên</th>

<th>Email</th>

<th>Phone</th>

<th>Role</th>

<th>Thao tác</th>

</tr>
</thead>
<tbody>

<?php foreach ($users as $account): ?>

<tr>

<td>

<?= (int)$account['id'] ?>

</td>


<td>

<img
    src="<?= htmlspecialchars(
        $account['avatar']
        ?: 'images/avatar.png'
    ) ?>"
    class="account-avatar"
>

</td>


<td>

<?= htmlspecialchars(
    $account['name']
) ?>

</td>


<td>

<?= htmlspecialchars(
    $account['email']
) ?>

</td>


<td>

<?= htmlspecialchars(
    $account['phone'] ?? ''
) ?>

</td>


<td>

<?php if (
    $account['role'] === 'admin'
): ?>

<span class="role-admin">

ADMIN

</span>

<?php else: ?>

<span class="role-user">

USER

</span>

<?php endif; ?>

</td>


<td>

<a href="admin_edit_user.php?id=<?= (int)$account['id'] ?>" class="action-btn edit-btn">

<i class="fa-solid fa-pen"></i>

Sửa

</a>


<?php if (
    (int)$account['id']
    !==
    $currentAdminId
): ?>

<a
    href="admin_delete_user.php?id=<?= (int)$account['id'] ?>"
    class="action-btn delete-btn"
    onclick="
        return confirm(
            'Bạn có chắc muốn xóa tài khoản này không?'
        );
    "
>

<i class="fa-solid fa-trash"></i>

Xóa

</a>

<?php else: ?>

<span class="self-label">

Đang đăng nhập

</span>

<?php endif; ?>


</td>


</tr>


<?php endforeach; ?>


</tbody>

</table>


</div>

</main>
</div>
    <a href="logout.php" class="logout-button">
        <i class="fa-solid fa-right-from-bracket"></i>
        Đăng xuất
    </a>
</div>

</body>

</html>