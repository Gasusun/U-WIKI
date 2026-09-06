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


$currentAdminId =
    (int)$_SESSION['user_id'];


$userId =
    (int)($_GET['id'] ?? 0);


if ($userId <= 0) {

    header("Location: admin_settings.php");

    exit();
}


/*
|--------------------------------------------------------------------------
| KHÔNG CHO ADMIN TỰ XÓA
|--------------------------------------------------------------------------
*/

if ($userId === $currentAdminId) {

    header(
        "Location: admin_settings.php"
    );

    exit();
}


$conn->begin_transaction();


try {

    /*
    |--------------------------------------------------------------------------
    | XÓA LIKE
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM community_likes
        WHERE user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA COMMENT
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM community_comments
        WHERE user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA HASHTAG LIÊN QUAN BÀI CỦA USER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE ph
        FROM community_post_hashtags ph
        INNER JOIN community_posts p
            ON p.id = ph.post_id
        WHERE p.user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA COMMENT CỦA NGƯỜI KHÁC TRONG BÀI CỦA USER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE c
        FROM community_comments c
        INNER JOIN community_posts p
            ON p.id = c.post_id
        WHERE p.user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA LIKE CỦA NGƯỜI KHÁC TRONG BÀI CỦA USER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE l
        FROM community_likes l
        INNER JOIN community_posts p
            ON p.id = l.post_id
        WHERE p.user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA BÀI VIẾT
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM community_posts
        WHERE user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA RANKING MATCH
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE m
        FROM ranking_session_matches m
        INNER JOIN ranking_sessions s
            ON s.id = m.session_id
        WHERE s.user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA RANKING SESSION
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM ranking_sessions
        WHERE user_id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | XÓA USER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM users
        WHERE id = ?
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();


    if ($stmt->affected_rows === 0) {

        throw new Exception(
            "Không tìm thấy tài khoản."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HOÀN TẤT
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    header(
        "Location: admin_settings.php"
    );

    exit();

} catch (Throwable $e) {

    $conn->rollback();

    die(
        "Không thể xóa tài khoản: " .
        htmlspecialchars(
            $e->getMessage()
        )
    );
}