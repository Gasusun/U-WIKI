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


$id =
    (int)($_GET['id'] ?? 0);


if ($id <= 0) {

    header("Location: admin_settings.php");

    exit();
}


/*
|--------------------------------------------------------------------------
| LẤY USER
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
    WHERE id = ?
    LIMIT 1
");


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$user =
    $stmt
    ->get_result()
    ->fetch_assoc();


if (!$user) {

    header("Location: admin_settings.php");

    exit();
}


$message = '';


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name =
        trim($_POST['name'] ?? '');

    $email =
        trim($_POST['email'] ?? '');

    $phone =
        trim($_POST['phone'] ?? '');

    $role =
        $_POST['role'] ?? 'user';

    $password =
        $_POST['password'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA
    |--------------------------------------------------------------------------
    */

    if ($name === '' || $email === '') {

        $message =
            'Tên và email không được để trống.';

    } elseif (
        $role !== 'user' &&
        $role !== 'admin'
    ) {

        $message =
            'Role không hợp lệ.';

    } else {


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA EMAIL TRÙNG
        |--------------------------------------------------------------------------
        */

        $check =
            $conn->prepare("
                SELECT id
                FROM users
                WHERE email = ?
                AND id != ?
                LIMIT 1
            ");


        $check->bind_param(
            "si",
            $email,
            $id
        );


        $check->execute();


        if (
            $check
            ->get_result()
            ->num_rows > 0
        ) {

            $message =
                'Email đã được sử dụng.';

        } else {


            /*
            |--------------------------------------------------------------------------
            | UPDATE CÓ PASSWORD
            |--------------------------------------------------------------------------
            */

            if ($password !== '') {

                $hashedPassword =
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );


                $update =
                    $conn->prepare("
                        UPDATE users
                        SET
                            name = ?,
                            email = ?,
                            phone = ?,
                            role = ?,
                            password = ?
                        WHERE id = ?
                    ");


                $update->bind_param(
                    "sssssi",
                    $name,
                    $email,
                    $phone,
                    $role,
                    $hashedPassword,
                    $id
                );

            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE KHÔNG ĐỔI PASSWORD
            |--------------------------------------------------------------------------
            */

            else {

                $update =
                    $conn->prepare("
                        UPDATE users
                        SET
                            name = ?,
                            email = ?,
                            phone = ?,
                            role = ?
                        WHERE id = ?
                    ");


                $update->bind_param(
                    "ssssi",
                    $name,
                    $email,
                    $phone,
                    $role,
                    $id
                );
            }


            if ($update->execute()) {

                /*
                |--------------------------------------------------------------
                | Upload avatar
                |--------------------------------------------------------------
                */

                if (
                    isset($_FILES['avatar']) &&
                    $_FILES['avatar']['error']
                    === UPLOAD_ERR_OK
                ) {

                    $file =
                        $_FILES['avatar'];


                    $allowed = [

                        'image/jpeg' => 'jpg',

                        'image/png' => 'png',

                        'image/webp' => 'webp'

                    ];


                    $mime =
                        mime_content_type(
                            $file['tmp_name']
                        );


                    if (
                        isset($allowed[$mime]) &&
                        $file['size']
                        <=
                        2 * 1024 * 1024
                    ) {

                        $uploadDir =
                            __DIR__ .
                            '/uploads/avatars/';


                        if (!is_dir($uploadDir)) {

                            mkdir(
                                $uploadDir,
                                0755,
                                true
                            );
                        }


                        $extension =
                            $allowed[$mime];


                        $fileName =
                            'admin_edit_' .
                            $id .
                            '_' .
                            time() .
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

                            $avatar =
                                'uploads/avatars/' .
                                $fileName;


                            $avatarUpdate =
                                $conn->prepare("
                                    UPDATE users
                                    SET avatar = ?
                                    WHERE id = ?
                                ");


                            $avatarUpdate->bind_param(
                                "si",
                                $avatar,
                                $id
                            );


                            $avatarUpdate->execute();
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Nếu sửa chính tài khoản Admin đang đăng nhập
                |--------------------------------------------------------------------------
                */

                if (
                    $id ===
                    (int)$_SESSION['user_id']
                ) {

                    $_SESSION['name'] =
                        $name;

                    $_SESSION['email'] =
                        $email;

                    $_SESSION['role'] =
                        $role;
                }


                $message =
                    'Cập nhật tài khoản thành công.';


                /*
                | Reload dữ liệu
                */

                $stmt =
                    $conn->prepare("
                        SELECT
                            id,
                            name,
                            email,
                            phone,
                            avatar,
                            role
                        FROM users
                        WHERE id = ?
                        LIMIT 1
                    ");


                $stmt->bind_param(
                    "i",
                    $id
                );


                $stmt->execute();


                $user =
                    $stmt
                    ->get_result()
                    ->fetch_assoc();
            }
        }
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

<title>Sửa tài khoản</title>

<link
    rel="stylesheet"
    href="indexStyle.css"
>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>

.edit-content {
    flex: 1;
    background: #f5f6f8;
    padding: 40px;
}

.edit-box {
    max-width: 650px;
    background: white;
    margin: auto;
    padding: 30px;
    border-radius: 10px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.avatar-preview {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin-bottom: 10px;
}

.save-btn {
    background: #3564ff;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.back-btn {
    background: #eee;
    color: #333;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
}

.message {
    padding: 10px;
    background: #e8f7e8;
    color: green;
    margin-bottom: 15px;
    border-radius: 6px;
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


<a
    href="community_admin.php"
    class="menu-item"
>

<i class="fa-solid fa-users"></i>

<span>Cộng đồng</span>

</a>


<a
    href="ranking_results.php"
    class="menu-item"
>

<i class="fa-solid fa-ranking-star"></i>

<span>Xếp hạng</span>

</a>


<a
    href="admin_settings.php"
    class="menu-item active"
>

<i class="fa-solid fa-gear"></i>

<span>Setting</span>

</a>

</nav>

</aside>


<main class="edit-content">


<div class="edit-box">

<h1>

<i class="fa-solid fa-user-pen"></i>

Sửa tài khoản

</h1>


<?php if ($message !== ''): ?>

<div class="message">

<?= htmlspecialchars($message) ?>

</div>

<?php endif; ?>


<form
    method="POST"
    enctype="multipart/form-data"
>


<div class="form-group">

<label>Avatar</label>

<img
    src="<?= htmlspecialchars(
        $user['avatar']
        ?: 'images/avatar.png'
    ) ?>"
    class="avatar-preview"
>

<input
    type="file"
    name="avatar"
    accept="image/jpeg,image/png,image/webp"
>

</div>


<div class="form-group">

<label>Tên</label>

<input
    type="text"
    name="name"
    value="<?= htmlspecialchars(
        $user['name']
    ) ?>"
    required
>

</div>


<div class="form-group">

<label>Email</label>

<input
    type="email"
    name="email"
    value="<?= htmlspecialchars(
        $user['email']
    ) ?>"
    required
>

</div>


<div class="form-group">

<label>Phone</label>

<input
    type="text"
    name="phone"
    value="<?= htmlspecialchars(
        $user['phone'] ?? ''
    ) ?>"
>

</div>


<div class="form-group">

<label>Quyền tài khoản</label>

<select name="role">

<option
    value="user"
    <?= $user['role'] === 'user'
        ? 'selected'
        : '' ?>
>

User

</option>


<option
    value="admin"
    <?= $user['role'] === 'admin'
        ? 'selected'
        : '' ?>
>

Admin

</option>

</select>

</div>


<div class="form-group">

<label>

Mật khẩu mới

</label>

<input
    type="password"
    name="password"
    placeholder="Để trống nếu không muốn đổi"
>

</div>


<button
    type="submit"
    class="save-btn"
>

<i class="fa-solid fa-save"></i>

Lưu thay đổi

</button>


<a
    href="admin_settings.php"
    class="back-btn"
>

Quay lại

</a>


</form>

</div>

</main>

</div>

</div>

</body>

</html>