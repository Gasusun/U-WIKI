<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if ($conn->connect_errno) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Không thể kết nối cơ sở dữ liệu.',
        'data' => []
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$conn->set_charset('utf8mb4');

$currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$currentRole   = $_SESSION['role'] ?? '';

function apiResponse(bool $success, string $message = '', array $data = []): void
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function requireLogin(): void
{
    global $currentUserId;
    if ($currentUserId <= 0) {
        apiResponse(false, 'Vui lòng đăng nhập để thực hiện chức năng này.');
    }
}

function isAdmin(): bool
{
    global $currentRole;
    return $currentRole === 'admin';
}

function cleanHashtag(string $tag): string
{
    $tag = trim($tag);
    $tag = ltrim($tag, '#');
    return mb_strtolower($tag, 'UTF-8');
}

/**
 * Tạo/lấy hashtag và liên kết với bài viết.
 */
function saveHashtags(mysqli $conn, int $postId, string $rawHashtags): void
{
    $tags = preg_split('/[\s,]+/u', trim($rawHashtags), -1, PREG_SPLIT_NO_EMPTY);
    $tags = array_values(array_unique(array_filter(array_map('cleanHashtag', $tags))));

    // Xóa liên kết cũ khi cập nhật bài.
    $stmt = $conn->prepare('DELETE FROM community_post_hashtags WHERE post_id = ?');
    $stmt->bind_param('i', $postId);
    $stmt->execute();

    foreach ($tags as $tag) {
        if ($tag === '') {
            continue;
        }

        $stmt = $conn->prepare('SELECT id FROM community_hashtags WHERE hashtag = ? LIMIT 1');
        $stmt->bind_param('s', $tag);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            $hashtagId = (int)$row['id'];
        } else {
            $stmt = $conn->prepare('INSERT INTO community_hashtags (hashtag) VALUES (?)');
            $stmt->bind_param('s', $tag);
            $stmt->execute();
            $hashtagId = (int)$conn->insert_id;
        }

        $stmt = $conn->prepare(
            'INSERT INTO community_post_hashtags (post_id, hashtag_id) VALUES (?, ?)'
        );
        $stmt->bind_param('ii', $postId, $hashtagId);
        $stmt->execute();
    }
}

/**
 * Lấy bài viết kèm user, hashtag, comment và số like.
 */
function getPosts(mysqli $conn, string $sort, string $search, int $viewerId): array
{
    $sort = in_array($sort, ['new', 'top', 'hot'], true) ? $sort : 'new';

    $orderBy = match ($sort) {
        'top' => 'like_count DESC, comment_count DESC, p.created_at DESC',
        'hot' => '(like_count * 3 + comment_count * 2 + LEAST(p.views, 50)) DESC, p.created_at DESC',
        default => 'p.created_at DESC'
    };

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
            COALESCE(NULLIF(u.avatar, ''), 'images/avatar.png') AS avatar,
            (
                SELECT COUNT(*)
                FROM community_likes l
                WHERE l.post_id = p.id
            ) AS like_count,
            (
                SELECT COUNT(*)
                FROM community_comments c
                WHERE c.post_id = p.id
            ) AS comment_count,
            EXISTS(
                SELECT 1
                FROM community_likes ml
                WHERE ml.post_id = p.id AND ml.user_id = ?
            ) AS liked
        FROM community_posts p
        INNER JOIN users u ON u.id = p.user_id
    ";

    $params = [$viewerId];
    $types = 'i';

    if ($search !== '') {
        $tag = cleanHashtag($search);
        $sql .= "
            INNER JOIN community_post_hashtags phs ON phs.post_id = p.id
            INNER JOIN community_hashtags hs ON hs.id = phs.hashtag_id
        ";
        $sql .= " WHERE hs.hashtag LIKE CONCAT('%', ?, '%') ";
        $params[] = $tag;
        $types .= 's';
    }

    $sql .= " ORDER BY {$orderBy} LIMIT 100";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        apiResponse(false, 'Lỗi truy vấn bài viết: ' . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];

    while ($post = $result->fetch_assoc()) {
        $postId = (int)$post['id'];

        // Hashtags
        $tagStmt = $conn->prepare("
            SELECT h.hashtag
            FROM community_hashtags h
            INNER JOIN community_post_hashtags ph ON ph.hashtag_id = h.id
            WHERE ph.post_id = ?
            ORDER BY h.hashtag ASC
        ");
        $tagStmt->bind_param('i', $postId);
        $tagStmt->execute();

        $tags = [];
        $tagResult = $tagStmt->get_result();
        while ($tag = $tagResult->fetch_assoc()) {
            $tags[] = $tag['hashtag'];
        }

        // Comments
        $commentStmt = $conn->prepare("
            SELECT
                c.id,
                c.post_id,
                c.user_id,
                c.content,
                c.created_at,
                c.updated_at,
                u.name AS username,
                COALESCE(NULLIF(u.avatar, ''), 'images/avatar.png') AS avatar
            FROM community_comments c
            INNER JOIN users u ON u.id = c.user_id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $commentStmt->bind_param('i', $postId);
        $commentStmt->execute();

        $comments = [];
        $commentResult = $commentStmt->get_result();
        while ($comment = $commentResult->fetch_assoc()) {
            $comment['id'] = (int)$comment['id'];
            $comment['post_id'] = (int)$comment['post_id'];
            $comment['user_id'] = (int)$comment['user_id'];
            $comments[] = $comment;
        }

        $post['id'] = $postId;
        $post['user_id'] = (int)$post['user_id'];
        $post['views'] = (int)$post['views'];
        $post['like_count'] = (int)$post['like_count'];
        $post['comment_count'] = (int)$post['comment_count'];
        $post['liked'] = (bool)$post['liked'];
        $post['tags'] = $tags;
        $post['comments'] = $comments;

        $posts[] = $post;
    }

    return $posts;
}

// =====================================================
// GET: LOAD POSTS
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sort = $_GET['sort'] ?? 'new';
    $search = trim($_GET['search'] ?? '');

    try {
        $posts = getPosts($conn, $sort, $search, $currentUserId);
        apiResponse(true, '', $posts);
    } catch (Throwable $e) {
        http_response_code(500);
        apiResponse(false, 'Không thể tải bài viết: ' . $e->getMessage());
    }
}

// =====================================================
// POST ACTIONS
// =====================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    apiResponse(false, 'Phương thức không được hỗ trợ.');
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {

        // -------------------------------------------------
        // CREATE POST
        // -------------------------------------------------
        case 'create_post':
            requireLogin();

            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $hashtags = trim($_POST['hashtags'] ?? '');

            if ($title === '' || $content === '') {
                apiResponse(false, 'Vui lòng nhập tiêu đề và nội dung.');
            }

            $imagePath = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    apiResponse(false, 'Không thể tải ảnh lên.');
                }

                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    apiResponse(false, 'Ảnh không được vượt quá 5MB.');
                }

                $allowed = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                    'image/gif'  => 'gif'
                ];

                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image']['tmp_name']);
                if (!isset($allowed[$mime])) {
                    apiResponse(false, 'Định dạng ảnh không được hỗ trợ.');
                }

                $uploadDir = __DIR__ . '/uploads/community/';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
                    apiResponse(false, 'Không thể tạo thư mục lưu ảnh.');
                }

                $fileName = 'post_' . $currentUserId . '_' . time() . '_' .
                    bin2hex(random_bytes(4)) . '.' . $allowed[$mime];

                $target = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    apiResponse(false, 'Không thể lưu ảnh.');
                }

                $imagePath = 'uploads/community/' . $fileName;
            }

            $stmt = $conn->prepare("
                INSERT INTO community_posts (user_id, title, content, image)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param('isss', $currentUserId, $title, $content, $imagePath);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể đăng bài: ' . $stmt->error);
            }

            $postId = (int)$conn->insert_id;
            saveHashtags($conn, $postId, $hashtags);

            apiResponse(true, 'Đăng bài thành công.', ['id' => $postId]);
            break;

        // -------------------------------------------------
        // UPDATE POST
        // -------------------------------------------------
        case 'update_post':
            requireLogin();

            $postId = (int)($_POST['post_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $hashtags = trim($_POST['hashtags'] ?? '');

            if ($postId <= 0 || $title === '' || $content === '') {
                apiResponse(false, 'Dữ liệu bài viết không hợp lệ.');
            }

            $stmt = $conn->prepare('SELECT user_id, image FROM community_posts WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $post = $stmt->get_result()->fetch_assoc();

            if (!$post) {
                apiResponse(false, 'Không tìm thấy bài viết.');
            }

            if ((int)$post['user_id'] !== $currentUserId && !isAdmin()) {
                apiResponse(false, 'Bạn không có quyền sửa bài viết này.');
            }

            $stmt = $conn->prepare("
                UPDATE community_posts
                SET title = ?, content = ?
                WHERE id = ?
            ");
            $stmt->bind_param('ssi', $title, $content, $postId);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể cập nhật bài viết.');
            }

            saveHashtags($conn, $postId, $hashtags);
            apiResponse(true, 'Cập nhật bài viết thành công.');
            break;

        // -------------------------------------------------
        // DELETE POST
        // -------------------------------------------------
        case 'delete_post':
            requireLogin();

            $postId = (int)($_POST['post_id'] ?? 0);

            $stmt = $conn->prepare('SELECT user_id, image FROM community_posts WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $postId);
            $stmt->execute();
            $post = $stmt->get_result()->fetch_assoc();

            if (!$post) {
                apiResponse(false, 'Không tìm thấy bài viết.');
            }

            if ((int)$post['user_id'] !== $currentUserId && !isAdmin()) {
                apiResponse(false, 'Bạn không có quyền xóa bài viết này.');
            }

            // Xóa các dữ liệu liên quan trước.
            $stmt = $conn->prepare('DELETE FROM community_likes WHERE post_id = ?');
            $stmt->bind_param('i', $postId);
            $stmt->execute();

            $stmt = $conn->prepare('DELETE FROM community_comments WHERE post_id = ?');
            $stmt->bind_param('i', $postId);
            $stmt->execute();

            $stmt = $conn->prepare('DELETE FROM community_post_hashtags WHERE post_id = ?');
            $stmt->bind_param('i', $postId);
            $stmt->execute();

            $stmt = $conn->prepare('DELETE FROM community_posts WHERE id = ?');
            $stmt->bind_param('i', $postId);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể xóa bài viết.');
            }

            // Chỉ xóa file nếu file nằm trong uploads/community.
            if (!empty($post['image']) && str_starts_with($post['image'], 'uploads/community/')) {
                $file = __DIR__ . '/' . $post['image'];
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            apiResponse(true, 'Xóa bài viết thành công.');
            break;

        // -------------------------------------------------
        // LIKE / UNLIKE
        // -------------------------------------------------
        case 'toggle_like':
            requireLogin();

            $postId = (int)($_POST['post_id'] ?? 0);

            $stmt = $conn->prepare('SELECT id FROM community_posts WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $postId);
            $stmt->execute();

            if (!$stmt->get_result()->fetch_assoc()) {
                apiResponse(false, 'Bài viết không tồn tại.');
            }

            $stmt = $conn->prepare(
                'SELECT post_id FROM community_likes WHERE post_id = ? AND user_id = ? LIMIT 1'
            );
            $stmt->bind_param('ii', $postId, $currentUserId);
            $stmt->execute();
            $liked = (bool)$stmt->get_result()->fetch_assoc();

            if ($liked) {
                $stmt = $conn->prepare(
                    'DELETE FROM community_likes WHERE post_id = ? AND user_id = ?'
                );
                $stmt->bind_param('ii', $postId, $currentUserId);
                $stmt->execute();
                apiResponse(true, 'Đã bỏ thích.');
            } else {
                $stmt = $conn->prepare(
                    'INSERT INTO community_likes (post_id, user_id) VALUES (?, ?)'
                );
                $stmt->bind_param('ii', $postId, $currentUserId);
                if (!$stmt->execute()) {
                    apiResponse(false, 'Không thể thích bài viết.');
                }
                apiResponse(true, 'Đã thích bài viết.');
            }
            break;

        // -------------------------------------------------
        // ADD COMMENT
        // -------------------------------------------------
        case 'add_comment':
            requireLogin();

            $postId = (int)($_POST['post_id'] ?? 0);
            $content = trim($_POST['content'] ?? '');

            if ($postId <= 0 || $content === '') {
                apiResponse(false, 'Nội dung bình luận không hợp lệ.');
            }

            $stmt = $conn->prepare('SELECT id FROM community_posts WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $postId);
            $stmt->execute();

            if (!$stmt->get_result()->fetch_assoc()) {
                apiResponse(false, 'Bài viết không tồn tại.');
            }

            $stmt = $conn->prepare("
                INSERT INTO community_comments (post_id, user_id, content)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param('iis', $postId, $currentUserId, $content);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể thêm bình luận.');
            }

            apiResponse(true, 'Bình luận thành công.', ['id' => (int)$conn->insert_id]);
            break;

        // -------------------------------------------------
        // UPDATE COMMENT
        // -------------------------------------------------
        case 'update_comment':
            requireLogin();

            $commentId = (int)($_POST['comment_id'] ?? 0);
            $content = trim($_POST['content'] ?? '');

            if ($commentId <= 0 || $content === '') {
                apiResponse(false, 'Nội dung bình luận không hợp lệ.');
            }

            $stmt = $conn->prepare(
                'SELECT user_id FROM community_comments WHERE id = ? LIMIT 1'
            );
            $stmt->bind_param('i', $commentId);
            $stmt->execute();
            $comment = $stmt->get_result()->fetch_assoc();

            if (!$comment) {
                apiResponse(false, 'Không tìm thấy bình luận.');
            }

            if ((int)$comment['user_id'] !== $currentUserId && !isAdmin()) {
                apiResponse(false, 'Bạn không có quyền sửa bình luận này.');
            }

            $stmt = $conn->prepare(
                'UPDATE community_comments SET content = ? WHERE id = ?'
            );
            $stmt->bind_param('si', $content, $commentId);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể cập nhật bình luận.');
            }

            apiResponse(true, 'Cập nhật bình luận thành công.');
            break;

        // -------------------------------------------------
        // DELETE COMMENT
        // -------------------------------------------------
        case 'delete_comment':
            requireLogin();

            $commentId = (int)($_POST['comment_id'] ?? 0);

            $stmt = $conn->prepare(
                'SELECT user_id FROM community_comments WHERE id = ? LIMIT 1'
            );
            $stmt->bind_param('i', $commentId);
            $stmt->execute();
            $comment = $stmt->get_result()->fetch_assoc();

            if (!$comment) {
                apiResponse(false, 'Không tìm thấy bình luận.');
            }

            if ((int)$comment['user_id'] !== $currentUserId && !isAdmin()) {
                apiResponse(false, 'Bạn không có quyền xóa bình luận này.');
            }

            $stmt = $conn->prepare('DELETE FROM community_comments WHERE id = ?');
            $stmt->bind_param('i', $commentId);

            if (!$stmt->execute()) {
                apiResponse(false, 'Không thể xóa bình luận.');
            }

            apiResponse(true, 'Xóa bình luận thành công.');
            break;

        default:
            apiResponse(false, 'Action không hợp lệ.');
    }
} catch (Throwable $e) {
    http_response_code(500);
    apiResponse(false, 'Lỗi server: ' . $e->getMessage());
}
