<?php

session_start();

require_once 'config.php';


/*
|--------------------------------------------------------------------------
| CHỈ ADMIN
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


/*
|--------------------------------------------------------------------------
| THÊM GIÁO VIÊN
|--------------------------------------------------------------------------
*/

$message = '';
$messageType = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name =
        trim($_POST['name'] ?? '');


    if ($name === '') {

        $message =
            'Vui lòng nhập tên giáo viên.';

        $messageType = 'error';

    } else {

        $imagePath =
            'images/avatar.png';


        /*
        |--------------------------------------------------------------------------
        | UPLOAD ẢNH
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $file =
                $_FILES['image'];


            if ($file['size'] > 5 * 1024 * 1024) {

                $message =
                    'Ảnh không được vượt quá 5MB.';

                $messageType = 'error';

            } else {

                $allowed = [

                    'image/jpeg' => 'jpg',

                    'image/png' => 'png',

                    'image/webp' => 'webp'

                ];


                $mime =
                    mime_content_type(
                        $file['tmp_name']
                    );


                if (!isset($allowed[$mime])) {

                    $message =
                        'Chỉ cho phép JPG, PNG hoặc WEBP.';

                    $messageType = 'error';

                } else {

                    $extension =
                        $allowed[$mime];


                    $uploadDir =
                        __DIR__ .
                        '/uploads/ranking/';


                    if (!is_dir($uploadDir)) {

                        mkdir(
                            $uploadDir,
                            0777,
                            true
                        );
                    }


                    $fileName =
                        'teacher_' .
                        time() .
                        '_' .
                        bin2hex(
                            random_bytes(4)
                        ) .
                        '.' .
                        $extension;


                    $target =
                        $uploadDir .
                        $fileName;


                    if (
                        move_uploaded_file(
                            $file['tmp_name'],
                            $target
                        )
                    ) {

                        $imagePath =
                            'uploads/ranking/' .
                            $fileName;

                    } else {

                        $message =
                            'Không thể lưu ảnh.';

                        $messageType = 'error';
                    }
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        if ($message === '') {

            $stmt =
                $conn->prepare("
                    INSERT INTO ranking_items
                    (
                        name,
                        image,
                        active
                    )
                    VALUES (?, ?, 1)
                ");


            $stmt->bind_param(
                "ss",
                $name,
                $imagePath
            );


            if ($stmt->execute()) {

                $message =
                    'Đã thêm giáo viên thành công.';

                $messageType = 'success';

            } else {

                $message =
                    'Không thể thêm giáo viên.';

                $messageType = 'error';
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| LẤY DANH SÁCH GIÁO VIÊN
|--------------------------------------------------------------------------
*/

$result =
    $conn->query("
        SELECT
            id,
            name,
            image,
            active
        FROM ranking_items
        ORDER BY id DESC
    ");


$teachers = [];


while (
    $row =
    $result->fetch_assoc()
) {

    $teachers[] = $row;
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

<title>Thêm giáo viên</title>

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

<style>

.add-container {
    padding: 35px;
    background: #f5f6f8;
    min-height: calc(100vh - 70px);
}

.add-box {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.add-box h1 {
    margin-top: 0;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    box-sizing: border-box;
    padding: 11px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.btn {
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-primary {
    background: #3564ff;
    color: white;
}

.btn-back {
    background: #eee;
    color: #333;
}

.message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
}

.success {
    background: #e8f7e8;
    color: green;
}

.error {
    background: #fdeaea;
    color: #d00;
}

.teacher-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

.teacher-table th,
.teacher-table td {
    border-bottom: 1px solid #eee;
    padding: 10px;
    text-align: left;
}

.teacher-image {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    object-fit: cover;
}

.status-active {
    color: green;
    font-weight: bold;
}

.status-off {
    color: #999;
}

</style>

</head>

<body>

<div class="page">

<header class="header">

    <div class="logo-area">

        <img
            src="images/logo.png"
            alt="NTTU"
        >

    </div>

</header>


<div class="main">

<aside class="sidebar">

<nav class="menu">

<a href="admin.php" class="menu-item">

    <i class="fa-solid fa-house"></i>

    <span>Home</span>

</a>


<a href="community_admin.php" class="menu-item">

    <i class="fa-solid fa-users"></i>

    <span>Cộng đồng</span>

</a>


<a href="ranking_results.php" class="menu-item active">

    <i class="fa-solid fa-ranking-star"></i>

    <span>Xếp hạng</span>

</a>


</nav>

</aside>


<main class="add-container">

<div class="add-box">

<h1>

<i class="fa-solid fa-user-plus"></i>

Thêm giáo viên

</h1>


<?php if ($message !== ''): ?>

<div class="message <?= $messageType ?>">

    <?= htmlspecialchars($message) ?>

</div>

<?php endif; ?>


<form
    method="POST"
    enctype="multipart/form-data"
>

<div class="form-group">

<label>

Tên giáo viên

</label>

<input
    type="text"
    name="name"
    placeholder="Nhập tên giáo viên"
    required
>

</div>


<div class="form-group">

<label>

Ảnh giáo viên

</label>

<input
    type="file"
    name="image"
    accept="image/jpeg,image/png,image/webp"
>

</div>


<button type="submit" class="btn btn-primary">
Thêm giáo viên
</button>

<a href="ranking_results.php">

<button type="button" class="btn btn-back">
Quay lại
</button>

</a>

</form>


<h2>

Danh sách giáo viên

</h2>


<table class="teacher-table">

<thead>

<tr>

<th>ID</th>

<th>Ảnh</th>

<th>Tên</th>

<th>Trạng thái</th>

</tr>

</thead>


<tbody>

<?php foreach ($teachers as $teacher): ?>

<tr>

<td>

<?= (int)$teacher['id'] ?>

</td>


<td>

<img
    src="<?= htmlspecialchars(
        $teacher['image']
    ) ?>"
    class="teacher-image"
>

</td>


<td>

<?= htmlspecialchars(
    $teacher['name']
) ?>

</td>


<td>

<?php if ($teacher['active']): ?>

<span class="status-active">

Đang sử dụng

</span>

<?php else: ?>

<span class="status-off">

Đã tắt

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

</div>

</body>
</html>