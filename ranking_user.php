<?php

session_start();

require_once 'config.php';


/* =========================================================
   LẤY USER ĐANG ĐĂNG NHẬP
========================================================= */

$userId = $_SESSION['user_id'] ?? null;

$user = null;

$avatar = 'images/avatar.png';


if ($userId) {

    $stmt = $conn->prepare("
        SELECT id, name, avatar
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if ($user && !empty($user['avatar'])) {
        $avatar = $user['avatar'];
    }
}


/* =========================================================
   XỬ LÝ VOTE
========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {

    /* Không đăng nhập thì tuyệt đối không cho vote */

    if (!$userId) {

        header("Location: login.php");

        exit();
    }


    $matchId = intval($_POST['match_id'] ?? 0);

    $selectedItemId = intval($_POST['selected_item_id'] ?? 0);


    if ($matchId <= 0 || $selectedItemId <= 0) {

        header("Location: ranking_user.php");

        exit();
    }


    /*
        Lấy session đang active
    */

    $stmt = $conn->prepare("
        SELECT id
        FROM ranking_sessions
        WHERE user_id = ?
        AND status = 'active'
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $session = $stmt->get_result()->fetch_assoc();


    if (!$session) {

        header("Location: ranking_user.php");

        exit();
    }


    $sessionId = $session['id'];


    /*
        Kiểm tra match có thuộc session
        và item có phải 1 trong 2 lựa chọn
    */

    $stmt = $conn->prepare("
        SELECT id, item_a_id, item_b_id, selected_item_id
        FROM ranking_session_matches
        WHERE id = ?
        AND session_id = ?
        LIMIT 1
    ");

    $stmt->bind_param(
        "ii",
        $matchId,
        $sessionId
    );

    $stmt->execute();

    $match = $stmt->get_result()->fetch_assoc();


    if (!$match) {

        header("Location: ranking_user.php");

        exit();
    }


    /*
        Nếu trận này đã vote rồi
        thì không cho vote lần 2
    */

    if ($match['selected_item_id'] !== null) {

        header("Location: ranking_user.php");

        exit();
    }


    /*
        Chỉ được chọn A hoặc B
    */

    if (
        $selectedItemId != $match['item_a_id']
        &&
        $selectedItemId != $match['item_b_id']
    ) {

        header("Location: ranking_user.php");

        exit();
    }


    /*
        Lưu kết quả
    */

    $stmt = $conn->prepare("
        UPDATE ranking_session_matches
        SET
            selected_item_id = ?,
            voted_at = NOW()
        WHERE id = ?
        AND session_id = ?
        AND selected_item_id IS NULL
    ");

    $stmt->bind_param(
        "iii",
        $selectedItemId,
        $matchId,
        $sessionId
    );

    $stmt->execute();


    /*
        Kiểm tra đã vote đủ 16 trận chưa
    */

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM ranking_session_matches
        WHERE session_id = ?
        AND selected_item_id IS NOT NULL
    ");

    $stmt->bind_param("i", $sessionId);

    $stmt->execute();

    $count = $stmt->get_result()->fetch_assoc();


    if ($count['total'] >= 16) {

        $stmt = $conn->prepare("
            UPDATE ranking_sessions
            SET
                status = 'completed',
                completed_at = NOW()
            WHERE id = ?
        ");

        $stmt->bind_param("i", $sessionId);

        $stmt->execute();


        /*
            Vote xong 16 trận
            chuyển sang trang kết quả
        */

        header("Location: ranking_results.php");

        exit();
    }


    /*
        Chưa đủ 16 trận
        tiếp tục trận tiếp theo
    */

    header("Location: ranking_user.php");

    exit();
}


/* =========================================================
   TẠO / LẤY SESSION VOTE
========================================================= */

$activeSession = null;


if ($userId) {

    /*
        Tìm session đang vote dở
    */

    $stmt = $conn->prepare("
        SELECT id
        FROM ranking_sessions
        WHERE user_id = ?
        AND status = 'active'
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $activeSession =
        $stmt->get_result()->fetch_assoc();


    /*
        Nếu chưa có session
        tạo session mới
    */

    if (!$activeSession) {

        /*
            Kiểm tra có đủ 32 đối tượng không
        */

        $check = $conn->query("
            SELECT COUNT(*) AS total
            FROM ranking_items
            WHERE active = 1
        ");

        $totalItems =
            $check->fetch_assoc()['total'];


        if ($totalItems >= 32) {

            /*
                Tạo session
            */

            $stmt = $conn->prepare("
                INSERT INTO ranking_sessions
                (user_id, status)
                VALUES (?, 'active')
            ");

            $stmt->bind_param(
                "i",
                $userId
            );

            $stmt->execute();

            $sessionId =
                $conn->insert_id;


            /*
                Lấy ngẫu nhiên 32 đối tượng
            */

            $items = [];

            $result = $conn->query("
                SELECT id
                FROM ranking_items
                WHERE active = 1
                ORDER BY RAND()
                LIMIT 32
            ");


            while ($row = $result->fetch_assoc()) {

                $items[] = $row['id'];
            }


            /*
                Chia thành 16 cặp

                1-2
                3-4
                5-6
                ...
                31-32
            */

            for ($i = 0; $i < 32; $i += 2) {

                $matchNo =
                    ($i / 2) + 1;

                $itemA =
                    intval($items[$i]);

                $itemB =
                    intval($items[$i + 1]);


                $stmt = $conn->prepare("
                    INSERT INTO ranking_session_matches
                    (
                        session_id,
                        match_no,
                        item_a_id,
                        item_b_id
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "iiii",
                    $sessionId,
                    $matchNo,
                    $itemA,
                    $itemB
                );

                $stmt->execute();
            }


            $activeSession = [
                'id' => $sessionId
            ];
        }
    }
}


/* =========================================================
   LẤY TRẬN CHƯA VOTE
========================================================= */

$currentMatch = null;

$completedCount = 0;


if ($userId && $activeSession) {

    $sessionId =
        intval($activeSession['id']);


    /*
        Đếm số trận đã vote
    */

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM ranking_session_matches
        WHERE session_id = ?
        AND selected_item_id IS NOT NULL
    ");

    $stmt->bind_param(
        "i",
        $sessionId
    );

    $stmt->execute();

    $completedCount =
        $stmt->get_result()
        ->fetch_assoc()['total'];


    /*
        Lấy trận đầu tiên chưa vote
    */

    $stmt = $conn->prepare("
        SELECT
            m.id,
            m.match_no,
            m.item_a_id,
            m.item_b_id,

            a.name AS item_a_name,
            a.image AS item_a_image,

            b.name AS item_b_name,
            b.image AS item_b_image

        FROM ranking_session_matches m

        INNER JOIN ranking_items a
            ON a.id = m.item_a_id

        INNER JOIN ranking_items b
            ON b.id = m.item_b_id

        WHERE m.session_id = ?

        AND m.selected_item_id IS NULL

        ORDER BY m.match_no ASC

        LIMIT 1
    ");

    $stmt->bind_param(
        "i",
        $sessionId
    );

    $stmt->execute();

    $currentMatch =
        $stmt->get_result()
        ->fetch_assoc();
}


/* =========================================================
   HTML
========================================================= */

?>

<!DOCTYPE html>

<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>NTTU - Xếp hạng</title>


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


<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar">

<nav class="menu">


<a
    href="<?= $userId ? 'user.php' : 'index.html' ?>"
    class="menu-item"
>

    <i class="fa-solid fa-house"></i>

    <span>Home</span>

</a>


<a
    href="<?= $userId ? 'map_users.php' : 'map.html' ?>"
    class="menu-item"
>

    <i class="fa-solid fa-map"></i>

    <span>Bản đồ trường</span>

</a>


<a
    href="<?= $userId ? 'account.php' : 'login.php' ?>"
    class="menu-item"
>

    <i class="fa-solid fa-user"></i>

    <span>Accounts</span>

</a>


<a
    href="<?= $userId ? 'course_users.php' : 'course.html' ?>"
    class="menu-item"
>

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


<!-- BUTTONS -->

<div class="ranking-actions">


    <a href="ranking_results.php">

        <button
            class="ranking-btn"
            type="button"
        >
            Check Tier List
        </button>

    </a>


    <?php if ($userId): ?>

        <a href="ranking_user.php">

            <button
                class="ranking-btn"
                type="button"
            >
                Make your own
            </button>

        </a>

    <?php else: ?>

        <a href="login.php">

            <button
                class="ranking-btn"
                type="button"
            >
                Make your own
            </button>

        </a>

    <?php endif; ?>


</div>


<?php if (!$userId): ?>


<!-- =====================================================
     CHƯA LOGIN
===================================================== -->

<div class="ranking-title">

    Rounds of 16

</div>


<div class="login-vote-message">

    Bạn cần
    <a href="login.php">đăng nhập</a>
    để tham gia bình chọn.

    <br>

    Người chưa đăng nhập chỉ có thể xem kết quả.

</div>


<?php

include 'ranking_results_content.php';

?>


<?php elseif (!$currentMatch): ?>


<!-- =====================================================
     ĐÃ VOTE XONG
===================================================== -->

<div class="ranking-completed">

    <h2>
        Bạn đã hoàn thành bình chọn!
    </h2>

    <p>
        Bạn đã vote đủ
        <?= intval($completedCount) ?>/16
        trận.
    </p>

    <br>

    <a href="ranking_results.php">

        <button
            class="ranking-btn"
            type="button"
        >
            Xem kết quả
        </button>

    </a>

</div>


<?php else: ?>


<!-- =====================================================
     MATCH
===================================================== -->

<h1 class="ranking-title">

    Rounds of 16 Match
    <?= intval($currentMatch['match_no']) ?>

</h1>


<div class="ranking-match">


    <!-- =================================================
         ITEM A
    ================================================= -->

    <div class="ranking-card">

        <img
            class="ranking-image"
            src="<?= htmlspecialchars(
                $currentMatch['item_a_image']
            ) ?>"
            alt=""
        >


        <form
            method="POST"
            action="ranking_user.php"
        >

            <input
                type="hidden"
                name="match_id"
                value="<?= intval($currentMatch['id']) ?>"
            >


            <input
                type="hidden"
                name="selected_item_id"
                value="<?= intval($currentMatch['item_a_id']) ?>"
            >


            <button
                class="vote-btn vote-blue"
                type="submit"
                name="vote"
            >

                <?= htmlspecialchars(
                    $currentMatch['item_a_name']
                ) ?>

            </button>

        </form>

    </div>


    <!-- =================================================
         ITEM B
    ================================================= -->

    <div class="ranking-card">
        <img
            class="ranking-image"
            src="<?= htmlspecialchars(
                $currentMatch['item_b_image']
            ) ?>"
            alt=""
        >

        <form
            method="POST"
            action="ranking_user.php"
        >

            <input
                type="hidden"
                name="match_id"
                value="<?= intval($currentMatch['id']) ?>"
            >


            <input
                type="hidden"
                name="selected_item_id"
                value="<?= intval($currentMatch['item_b_id']) ?>"
            >


            <button
                class="vote-btn vote-red"
                type="submit"
                name="vote"
            >

                <?= htmlspecialchars(
                    $currentMatch['item_b_name']
                ) ?>

            </button>

        </form>

    </div>


</div>


<?php endif; ?>


</main>


</div>

</div>


</body>

</html>