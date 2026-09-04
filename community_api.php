<?php

session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');


// =====================================================
// HÀM TRẢ JSON
// =====================================================

function response($success, $message = '', $data = [])
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =====================================================
// KIỂM TRA USER
// =====================================================

$userId = $_SESSION['user_id'] ?? null;


// =====================================================
// GET - LẤY DANH SÁCH BÀI VIẾT
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sort = $_GET['sort'] ?? 'new';

    $search = trim($_GET['search'] ?? '');

    /*
    Nếu người dùng nhập:
    #NTTU
    thì bỏ dấu #
    */

    $search = ltrim($search, '#');

    $sql = "
        SELECT
            p.id,
            p.user_id,
            p.title,
            p.content,
            p.image,
            p.views,
            p.created_at,
            p.updated_at,

            u.name AS username,
            u.avatar,

            (
                SELECT COUNT(*)
                FROM community_likes l
                WHERE l.post_id = p.id
            ) AS like_count,

            (
                SELECT COUNT(*)
                FROM community_comments c
                WHERE c.post_id = p.id
            ) AS comment_count

        FROM community_posts p

        INNER JOIN users u
            ON u.id = p.user_id
    ";


    $params = [];
    $types = "";


    // =================================================
    // TÌM THEO HASHTAG
    // =================================================

    if ($search !== '') {

        $sql .= "
            INNER JOIN community_post_hashtags ph
                ON ph.post_id = p.id

            INNER JOIN community_hashtags h
                ON h.id = ph.hashtag_id

            WHERE h.hashtag = ?
        ";

        $params[] = $search;
        $types .= "s";
    }


    // =================================================
    // SẮP XẾP
    // =================================================

    if ($sort === 'top') {

        $sql .= "
            ORDER BY like_count DESC,
                     p.created_at DESC
        ";

    } elseif ($sort === 'hot') {

        $sql .= "
            ORDER BY
                (like_count * 3 + comment_count * 2) DESC,
                p.created_at DESC
        ";

    } else {

        $sql .= "
            ORDER BY p.created_at DESC
        ";
    }


    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        response(false, $conn->error);
    }


    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }


    $stmt->execute();

    $result = $stmt->get_result();

    $posts = [];


    while ($post = $result->fetch_assoc()) {

        $postId = (int)$post['id'];


        // =============================================
        // LẤY HASHTAG
        // =============================================

        $tagStmt = $conn->prepare("
            SELECT h.hashtag

            FROM community_hashtags h

            INNER JOIN community_post_hashtags ph
                ON ph.hashtag_id = h.id

            WHERE ph.post_id = ?

            ORDER BY h.hashtag ASC
        ");

        $tagStmt->bind_param("i", $postId);

        $tagStmt->execute();

        $tagResult = $tagStmt->get_result();

        $tags = [];


        while ($tag = $tagResult->fetch_assoc()) {
            $tags[] = $tag['hashtag'];
        }


        // =============================================
        // LẤY COMMENT
        // =============================================

        $commentStmt = $conn->prepare("
            SELECT
                c.id,
                c.user_id,
                c.content,
                c.created_at,
                c.updated_at,

                u.name AS username,
                u.avatar

            FROM community_comments c

            INNER JOIN users u
                ON u.id = c.user_id

            WHERE c.post_id = ?

            ORDER BY c.created_at ASC
        ");

        $commentStmt->bind_param("i", $postId);

        $commentStmt->execute();

        $commentResult = $commentStmt->get_result();

        $comments = [];


        while ($comment = $commentResult->fetch_assoc()) {

            $comments[] = $comment;
        }


        // =============================================
        // KIỂM TRA USER ĐÃ LIKE CHƯA
        // =============================================

        $liked = false;


        if ($userId) {

            $likeStmt = $conn->prepare("
                SELECT 1
                FROM community_likes
                WHERE post_id = ?
                AND user_id = ?
                LIMIT 1
            ");

            $likeStmt->bind_param(
                "ii",
                $postId,
                $userId
            );

            $likeStmt->execute();

            $likeResult = $likeStmt->get_result();

            $liked = $likeResult->num_rows > 0;
        }


        $post['tags'] = $tags;

        $post['comments'] = $comments;

        $post['liked'] = $liked;

        $posts[] = $post;
    }


    response(
        true,
        'Lấy bài viết thành công',
        $posts
    );
}


// =====================================================
// TỪ ĐÂY TRỞ XUỐNG LÀ POST ACTION
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    response(false, 'Phương thức không hợp lệ');
}


// =====================================================
// BẮT BUỘC ĐĂNG NHẬP
// =====================================================

if (!$userId) {

    response(
        false,
        'Bạn cần đăng nhập để thực hiện chức năng này'
    );
}


$action = $_POST['action'] ?? '';


// =====================================================
// 1. TẠO BÀI VIẾT
// =====================================================

if ($action === 'create_post') {

    $title = trim($_POST['title'] ?? '');

    $content = trim($_POST['content'] ?? '');

    $hashtags = trim($_POST['hashtags'] ?? '');


    if ($title === '') {
        response(false, 'Vui lòng nhập tiêu đề');
    }


    if ($content === '') {
        response(false, 'Vui lòng nhập nội dung');
    }


    if ($hashtags === '') {
        response(false, 'Vui lòng nhập ít nhất một hashtag');
    }


    // =============================================
    // XỬ LÝ ẢNH
    // =============================================

    $imagePath = null;


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

            response(false, 'Upload ảnh thất bại');
        }


        // Giới hạn 5MB

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {

            response(
                false,
                'Ảnh không được vượt quá 5MB'
            );
        }


        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];


        $mime = mime_content_type(
            $_FILES['image']['tmp_name']
        );


        if (!isset($allowed[$mime])) {

            response(
                false,
                'Chỉ hỗ trợ JPG, PNG, WEBP hoặc GIF'
            );
        }


        $extension = $allowed[$mime];


        $uploadDir = __DIR__ . '/uploads/community/';


        if (!is_dir($uploadDir)) {

            mkdir(
                $uploadDir,
                0777,
                true
            );
        }


        $fileName =
            'post_' .
            $userId .
            '_' .
            time() .
            '_' .
            bin2hex(random_bytes(4)) .
            '.' .
            $extension;


        $target = $uploadDir . $fileName;


        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $target
        )) {

            response(
                false,
                'Không thể lưu ảnh'
            );
        }


        $imagePath =
            'uploads/community/' .
            $fileName;
    }


    // =============================================
    // INSERT BÀI
    // =============================================

    $stmt = $conn->prepare("
        INSERT INTO community_posts
        (user_id, title, content, image)
        VALUES (?, ?, ?, ?)
    ");


    $stmt->bind_param(
        "isss",
        $userId,
        $title,
        $content,
        $imagePath
    );


    if (!$stmt->execute()) {

        response(
            false,
            'Không thể tạo bài viết'
        );
    }


    $postId = $conn->insert_id;


    // =============================================
    // XỬ LÝ HASHTAG
    // =============================================

    preg_match_all(
        '/#[\p{L}\p{N}_-]+/u',
        $hashtags,
        $matches
    );


    $tags = [];


    foreach ($matches[0] as $tag) {

        $tag = mb_strtolower(
            ltrim($tag, '#'),
            'UTF-8'
        );


        if ($tag !== '') {

            $tags[$tag] = true;
        }
    }


    if (empty($tags)) {

        response(
            false,
            'Hashtag không hợp lệ'
        );
    }


    foreach (array_keys($tags) as $tag) {

        // =========================================
        // TẠO HASHTAG NẾU CHƯA CÓ
        // =========================================

        $tagStmt = $conn->prepare("
            INSERT INTO community_hashtags
            (hashtag)

            VALUES (?)

            ON DUPLICATE KEY UPDATE
            id = LAST_INSERT_ID(id)
        ");


        $tagStmt->bind_param(
            "s",
            $tag
        );


        $tagStmt->execute();


        $hashtagId = $conn->insert_id;


        // =========================================
        // LIÊN KẾT
        // =========================================

        $linkStmt = $conn->prepare("
            INSERT IGNORE INTO
            community_post_hashtags
            (post_id, hashtag_id)

            VALUES (?, ?)
        ");


        $linkStmt->bind_param(
            "ii",
            $postId,
            $hashtagId
        );


        $linkStmt->execute();
    }


    response(
        true,
        'Đăng bài thành công'
    );
}


// =====================================================
// 2. SỬA BÀI
// =====================================================

if ($action === 'update_post') {

    $postId = (int)($_POST['post_id'] ?? 0);

    $title = trim($_POST['title'] ?? '');

    $content = trim($_POST['content'] ?? '');

    $hashtags = trim($_POST['hashtags'] ?? '');


    if (!$postId) {
        response(false, 'Bài viết không hợp lệ');
    }


    if ($title === '' || $content === '') {

        response(
            false,
            'Tiêu đề và nội dung không được để trống'
        );
    }


    // =============================================
    // KIỂM TRA QUYỀN SỞ HỮU
    // =============================================

    $check = $conn->prepare("
        SELECT image
        FROM community_posts
        WHERE id = ?
        AND user_id = ?
    ");

    $check->bind_param(
        "ii",
        $postId,
        $userId
    );

    $check->execute();

    $checkResult = $check->get_result();

    if ($checkResult->num_rows === 0) {

        response(
            false,
            'Bạn không có quyền sửa bài viết này'
        );
    }


    $oldPost = $checkResult->fetch_assoc();

    $imagePath = $oldPost['image'];


    // =============================================
    // NẾU CÓ ẢNH MỚI
    // =============================================

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {

            response(false, 'Ảnh tối đa 5MB');
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];


        $mime = mime_content_type(
            $_FILES['image']['tmp_name']
        );


        if (!isset($allowed[$mime])) {

            response(false, 'Định dạng ảnh không hợp lệ');
        }


        $extension = $allowed[$mime];


        $uploadDir =
            __DIR__ . '/uploads/community/';


        if (!is_dir($uploadDir)) {

            mkdir(
                $uploadDir,
                0777,
                true
            );
        }


        $fileName =
            'post_' .
            $userId .
            '_' .
            time() .
            '_' .
            bin2hex(random_bytes(4)) .
            '.' .
            $extension;


        $target =
            $uploadDir .
            $fileName;


        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $target
        )) {

            response(false, 'Không thể lưu ảnh mới');
        }


        // Xóa ảnh cũ

        if (
            !empty($imagePath) &&
            file_exists(__DIR__ . '/' . $imagePath)
        ) {

            @unlink(
                __DIR__ . '/' . $imagePath
            );
        }


        $imagePath =
            'uploads/community/' .
            $fileName;
    }


    // =============================================
    // UPDATE POST
    // =============================================

    $stmt = $conn->prepare("
        UPDATE community_posts

        SET
            title = ?,
            content = ?,
            image = ?

        WHERE id = ?
        AND user_id = ?
    ");

    $stmt->bind_param(
        "sssii",
        $title,
        $content,
        $imagePath,
        $postId,
        $userId
    );


    $stmt->execute();


    // =============================================
    // XÓA HASHTAG CŨ
    // =============================================

    $deleteTags = $conn->prepare("
        DELETE FROM community_post_hashtags
        WHERE post_id = ?
    ");

    $deleteTags->bind_param(
        "i",
        $postId
    );

    $deleteTags->execute();


    // =============================================
    // THÊM HASHTAG MỚI
    // =============================================

    preg_match_all(
        '/#[\p{L}\p{N}_-]+/u',
        $hashtags,
        $matches
    );


    $tags = [];


    foreach ($matches[0] as $tag) {

        $tag = mb_strtolower(
            ltrim($tag, '#'),
            'UTF-8'
        );


        if ($tag !== '') {

            $tags[$tag] = true;
        }
    }


    foreach (array_keys($tags) as $tag) {

        $tagStmt = $conn->prepare("
            INSERT INTO community_hashtags
            (hashtag)

            VALUES (?)

            ON DUPLICATE KEY UPDATE
            id = LAST_INSERT_ID(id)
        ");


        $tagStmt->bind_param(
            "s",
            $tag
        );


        $tagStmt->execute();


        $hashtagId = $conn->insert_id;


        $linkStmt = $conn->prepare("
            INSERT IGNORE INTO
            community_post_hashtags
            (post_id, hashtag_id)

            VALUES (?, ?)
        ");


        $linkStmt->bind_param(
            "ii",
            $postId,
            $hashtagId
        );


        $linkStmt->execute();
    }


    response(
        true,
        'Đã sửa bài viết'
    );
}


// =====================================================
// 3. XÓA BÀI
// =====================================================

if ($action === 'delete_post') {

    $postId = (int)($_POST['post_id'] ?? 0);


    $stmt = $conn->prepare("
        SELECT image
        FROM community_posts
        WHERE id = ?
        AND user_id = ?
    ");


    $stmt->bind_param(
        "ii",
        $postId,
        $userId
    );


    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows === 0) {

        response(
            false,
            'Bạn không có quyền xóa bài này'
        );
    }


    $post = $result->fetch_assoc();


    // Xóa ảnh

    if (
        !empty($post['image']) &&
        file_exists(__DIR__ . '/' . $post['image'])
    ) {

        @unlink(
            __DIR__ . '/' . $post['image']
        );
    }


    $delete = $conn->prepare("
        DELETE FROM community_posts
        WHERE id = ?
        AND user_id = ?
    ");


    $delete->bind_param(
        "ii",
        $postId,
        $userId
    );


    $delete->execute();


    response(
        true,
        'Đã xóa bài viết'
    );
}


// =====================================================
// 4. LIKE / UNLIKE
// =====================================================

if ($action === 'toggle_like') {

    $postId = (int)($_POST['post_id'] ?? 0);


    $check = $conn->prepare("
        SELECT 1
        FROM community_likes
        WHERE post_id = ?
        AND user_id = ?
    ");


    $check->bind_param(
        "ii",
        $postId,
        $userId
    );


    $check->execute();


    $result = $check->get_result();


    if ($result->num_rows > 0) {

        $delete = $conn->prepare("
            DELETE FROM community_likes
            WHERE post_id = ?
            AND user_id = ?
        ");


        $delete->bind_param(
            "ii",
            $postId,
            $userId
        );


        $delete->execute();

        $liked = false;

    } else {

        $insert = $conn->prepare("
            INSERT INTO community_likes
            (post_id, user_id)

            VALUES (?, ?)
        ");


        $insert->bind_param(
            "ii",
            $postId,
            $userId
        );


        $insert->execute();

        $liked = true;
    }


    // Đếm like

    $count = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM community_likes
        WHERE post_id = ?
    ");


    $count->bind_param(
        "i",
        $postId
    );


    $count->execute();


    $likeCount =
        $count->get_result()
        ->fetch_assoc()['total'];


    response(
        true,
        $liked ? 'Đã thích bài viết' : 'Đã bỏ thích',
        [
            'liked' => $liked,
            'like_count' => $likeCount
        ]
    );
}


// =====================================================
// 5. THÊM COMMENT
// =====================================================

if ($action === 'add_comment') {

    $postId = (int)($_POST['post_id'] ?? 0);

    $content = trim($_POST['content'] ?? '');


    if ($content === '') {

        response(
            false,
            'Comment không được để trống'
        );
    }


    $stmt = $conn->prepare("
        INSERT INTO community_comments
        (post_id, user_id, content)

        VALUES (?, ?, ?)
    ");


    $stmt->bind_param(
        "iis",
        $postId,
        $userId,
        $content
    );


    if (!$stmt->execute()) {

        response(
            false,
            'Không thể thêm comment'
        );
    }


    response(
        true,
        'Đã thêm comment'
    );
}


// =====================================================
// 6. SỬA COMMENT
// =====================================================

if ($action === 'update_comment') {

    $commentId =
        (int)($_POST['comment_id'] ?? 0);

    $content =
        trim($_POST['content'] ?? '');


    if ($content === '') {

        response(
            false,
            'Comment không được để trống'
        );
    }


    $stmt = $conn->prepare("
        UPDATE community_comments

        SET content = ?

        WHERE id = ?
        AND user_id = ?
    ");


    $stmt->bind_param(
        "sii",
        $content,
        $commentId,
        $userId
    );


    $stmt->execute();


    if ($stmt->affected_rows === 0) {

        response(
            false,
            'Bạn không có quyền sửa comment này'
        );
    }


    response(
        true,
        'Đã sửa comment'
    );
}

// =====================================================
// 7. XÓA COMMENT
// =====================================================

if ($action === 'delete_comment') {

    $commentId =
        (int)($_POST['comment_id'] ?? 0);


    $stmt = $conn->prepare("
        DELETE FROM community_comments

        WHERE id = ?
        AND user_id = ?
    ");


    $stmt->bind_param(
        "ii",
        $commentId,
        $userId
    );

    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        response(
            false,
            'Bạn không có quyền xóa comment này'
        );
    }
    response(
        true,
        'Đã xóa comment'
    );
}

response(
    false,
    'Action không tồn tại'
);