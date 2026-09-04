<?php

session_start();

require_once 'config.php';


// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}


$userId = $_SESSION['user_id'];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');


// =========================
// KIỂM TRA DỮ LIỆU
// =========================

if ($name === '' || $email === '') {

    $_SESSION['account_message'] =
        "Vui lòng nhập đầy đủ thông tin.";

    header("Location: account.php");
    exit();

}
// =========================
// KIỂM TRA EMAIL TRÙNG
// =========================

$stmt = $conn->prepare("
    SELECT id
    FROM users
    WHERE email = ?
    AND id != ?
");

$stmt->bind_param("si", $email, $userId);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $_SESSION['account_message'] =
        "Email này đã được sử dụng.";

    header("Location: account.php");
    exit();

}


// =========================
// LẤY AVATAR HIỆN TẠI
// =========================

$stmt = $conn->prepare("
    SELECT avatar
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$currentUser = $result->fetch_assoc();

$avatar = $currentUser['avatar'];


// =========================
// UPLOAD AVATAR
// =========================

if (
    isset($_FILES['avatar']) &&
    $_FILES['avatar']['error'] === UPLOAD_ERR_OK
) {

    $file = $_FILES['avatar'];

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $mimeType = mime_content_type($file['tmp_name']);

    if (!isset($allowedTypes[$mimeType])) {

        $_SESSION['account_message'] =
            "Chỉ được upload JPG, PNG hoặc WEBP.";

        header("Location: account.php");
        exit();

    }


    // Giới hạn 2MB
    if ($file['size'] > 2 * 1024 * 1024) {

        $_SESSION['account_message'] =
            "Ảnh không được lớn hơn 2MB.";
        header("Location: account.php");
        exit();
    }


    $extension = $allowedTypes[$mimeType];
    $newFileName =
        'avatar_' .
        $userId .
        '_' .
        time() .
        '.' .
        $extension;
    $uploadDir = __DIR__ . '/uploads/avatars/';

    if (!is_dir($uploadDir)) {

        mkdir($uploadDir, 0755, true);
    }

    $targetPath =
        $uploadDir . $newFileName;


    if (move_uploaded_file(
        $file['tmp_name'],
        $targetPath
    )) {

        $avatar =
            'uploads/avatars/' . $newFileName;

    }

}
// =========================
// UPDATE DATABASE
// =========================

$stmt = $conn->prepare("
    UPDATE users
    SET
        name = ?,
        email = ?,
        phone = ?,
        avatar = ?
    WHERE id = ?
");


$stmt->bind_param(
    "ssssi",
    $name,
    $email,
    $phone,
    $avatar,
    $userId
);


if ($stmt->execute()) {

    // Cập nhật 
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['avatar'] = $avatar;

    $_SESSION['account_message'] =
        "Cập nhật tài khoản thành công.";

} else {

    $_SESSION['account_message'] =
        "Có lỗi xảy ra khi cập nhật.";

}

header("Location: account.php");
exit();
?>