<?php

session_start();
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();
}


$userId = $_SESSION['user_id'];


$stmt = $conn->prepare("
    SELECT
        id,
        name,
        avatar

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
    $stmt
    ->get_result()
    ->fetch_assoc();


$avatar =
    !empty($user['avatar'])
    ? $user['avatar']
    : 'images/avatar.png';

?>

<!DOCTYPE html>

<html lang="vi">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>NTTU - Cộng đồng</title>


<link
    rel="stylesheet"
    href="indexStyle.css"
>


<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>


<style>

.community-content {
    flex: 1;
    background: #f5f6f8;
    padding: 30px 38px;
    overflow-y: auto;
}

.community-search {
    max-width: 850px;
    margin-bottom: 20px;
}

.community-search-box {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    height: 42px;
    display: flex;
    align-items: center;
    padding: 0 14px;
}

.community-search-box i {
    color: #888;
    margin-right: 10px;
}

.community-search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 13px;
}

.create-post-box {
    max-width: 850px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 20px;
}

.create-post-box h2 {
    font-size: 17px;
    margin-top: 0;
}

.form-input {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 10px;
    outline: none;
    font-family: inherit;
}

.form-input:focus {
    border-color: #3564ff;
}

.form-textarea {
    min-height: 110px;
    resize: vertical;
}

.post-image-preview {
    max-width: 250px;
    max-height: 180px;
    border-radius: 6px;
    margin-bottom: 10px;
    display: none;
}

.form-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.image-label {
    cursor: pointer;
    color: #3564ff;
    font-size: 13px;
}

.primary-btn {
    border: none;
    background: #3564ff;
    color: white;
    padding: 9px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.post-filter {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}

.filter-btn {
    border: none;
    background: #e9ebee;
    color: #777;
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
}

.filter-btn.active {
    background: #3564ff;
    color: white;
}

.posts-container {
    width: 100%;
    max-width: 850px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.post-card {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 20px;
}

.post-header {
    display: flex;
    align-items: center;
}

.post-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
}

.post-user {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.post-user strong {
    font-size: 14px;
}

.post-user span {
    font-size: 11px;
    color: #999;
}

.post-menu {
    margin-left: auto;
    display: flex;
    gap: 5px;
}

.post-menu button,
.comment-actions button {
    border: none;
    background: transparent;
    cursor: pointer;
    color: #777;
    padding: 5px;
}

.post-menu button:hover,
.comment-actions button:hover {
    color: #3564ff;
}

.post-title {
    margin-top: 16px;
    font-size: 17px;
}

.post-description {
    color: #666;
    line-height: 1.6;
    white-space: pre-wrap;
}

.post-image {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
    border-radius: 7px;
    margin-top: 12px;
}

.post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
}

.post-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.post-tag {
    color: #3564ff;
    background: #eef2ff;
    padding: 5px 9px;
    border-radius: 5px;
    font-size: 11px;
    cursor: pointer;
}

.post-stats {
    display: flex;
    gap: 15px;
    align-items: center;
}

.like-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    color: #777;
}

.like-btn.liked {
    color: #e53935;
}

.comments-area {
    margin-top: 20px;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.comment-item {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

.comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-body {
    flex: 1;
    background: #f4f5f7;
    border-radius: 8px;
    padding: 8px 12px;
}

.comment-top {
    display: flex;
    justify-content: space-between;
}

.comment-name {
    font-weight: bold;
    font-size: 12px;
}

.comment-content {
    font-size: 12px;
    color: #555;
    margin-top: 4px;
    white-space: pre-wrap;
}

.comment-actions {
    display: flex;
    gap: 3px;
}

.comment-form {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.comment-input {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 9px;
    outline: none;
}

.comment-submit {
    border: none;
    background: #3564ff;
    color: white;
    border-radius: 6px;
    padding: 0 15px;
    cursor: pointer;
}

.no-post {
    text-align: center;
    padding: 40px;
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


    <div class="header-right">

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

    </div>

</header>


<div class="main">


<aside class="sidebar">

<nav class="menu">


<a
    href="user.php"
    class="menu-item"
>

<i class="fa-solid fa-house"></i>

<span>Home</span>

</a>


<a
    href="map_users.php"
    class="menu-item"
>

<i class="fa-solid fa-map"></i>

<span>Bản đồ trường</span>

</a>


<a
    href="account.php"
    class="menu-item"
>

<i class="fa-solid fa-user"></i>

<span>Accounts</span>

</a>


<a
    href="course_users.php"
    class="menu-item"
>

<i class="fa-solid fa-book-open"></i>

<span>Môn học</span>

</a>


<a
    href="community_users.php"
    class="menu-item active"
>

<i class="fa-solid fa-users"></i>

<span>Cộng đồng</span>

</a>


</nav>

</aside>


<main class="community-content">


<!-- SEARCH -->

<div class="community-search">

    <div class="community-search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="hashtag-search"
            placeholder="Tìm bài viết theo hashtag, ví dụ #NTTU"
        >

    </div>

</div>


<!-- CREATE POST -->

<div class="create-post-box">

    <h2>

        <i class="fa-solid fa-pen"></i>

        Đăng bài viết

    </h2>


    <form
        id="create-post-form"
        enctype="multipart/form-data"
    >

        <input
            type="text"
            name="title"
            class="form-input"
            placeholder="Tiêu đề bài viết"
            required
        >


        <textarea
            name="content"
            class="form-input form-textarea"
            placeholder="Bạn muốn chia sẻ điều gì?"
            required
        ></textarea>


        <input
            type="text"
            name="hashtags"
            class="form-input"
            placeholder="Hashtag: #NTTU #CNTT"
            required
        >


        <img
            id="image-preview"
            class="post-image-preview"
        >


        <div class="form-bottom">

            <label class="image-label">

                <i class="fa-solid fa-image"></i>

                Thêm ảnh

                <input
                    type="file"
                    name="image"
                    id="post-image"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    hidden
                >

            </label>


            <button
                type="submit"
                class="primary-btn"
            >

                <i class="fa-solid fa-paper-plane"></i>

                Đăng bài

            </button>

        </div>

    </form>

</div>


<!-- FILTER -->

<div class="post-filter">

    <button
        class="filter-btn active"
        data-sort="new"
    >
        <i class="fa-solid fa-clock"></i>
        Mới nhất
    </button>


    <button
        class="filter-btn"
        data-sort="top"
    >
        <i class="fa-solid fa-heart"></i>
        Top
    </button>


    <button
        class="filter-btn"
        data-sort="hot"
    >
        <i class="fa-solid fa-fire"></i>
        Hot
    </button>

</div>


<div
    id="posts-container"
    class="posts-container"
>

    <p>Đang tải bài viết...</p>

</div>


</main>

</div>

</div>


<script>

const CURRENT_USER_ID =
    <?= (int)$userId ?>;


let currentSort = "new";


function escapeHtml(text) {

    const div =
        document.createElement("div");

    div.textContent =
        text ?? "";

    return div.innerHTML;
}


function formatDate(date) {

    return new Date(date)
        .toLocaleString("vi-VN");

}


// =================================================
// LOAD POSTS
// =================================================

async function loadPosts() {

    const search =
        document
        .getElementById("hashtag-search")
        .value
        .trim();


    const url =
        "community_api.php?sort=" +
        encodeURIComponent(currentSort) +
        "&search=" +
        encodeURIComponent(search);


    const response =
        await fetch(url);


    const result =
        await response.json();


    const container =
        document.getElementById(
            "posts-container"
        );


    if (!result.success) {

        container.innerHTML =
            "<p>Không thể tải bài viết.</p>";

        return;
    }


    const posts =
        result.data;


    if (posts.length === 0) {

        container.innerHTML =
            '<p class="no-post">Chưa có bài viết nào.</p>';

        return;
    }


    container.innerHTML = "";


    posts.forEach(post => {

        container.innerHTML +=
            createPostHtml(post);

    });


    attachEvents();

}


// =================================================
// CREATE POST HTML
// =================================================

function createPostHtml(post) {

    const isOwner =
        Number(post.user_id) ===
        CURRENT_USER_ID;


    let tags = "";


    post.tags.forEach(tag => {

        tags += `

            <span
                class="post-tag"
                data-tag="${escapeHtml(tag)}"
            >
                #${escapeHtml(tag)}
            </span>

        `;

    });


    let comments = "";


    post.comments.forEach(comment => {

        const isCommentOwner =
            Number(comment.user_id) ===
            CURRENT_USER_ID;


        comments += `

            <div
                class="comment-item"
                data-comment-id="${comment.id}"
            >

                <img
                    src="${escapeHtml(
                        comment.avatar ||
                        "images/avatar.png"
                    )}"
                    class="comment-avatar"
                >


                <div class="comment-body">

                    <div class="comment-top">

                        <div class="comment-name">

                            ${escapeHtml(
                                comment.username
                            )}

                        </div>


                        ${
                            isCommentOwner
                            ?
                            `
                            <div class="comment-actions">

                                <button
                                    class="edit-comment"
                                    data-id="${comment.id}"
                                    title="Sửa"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                </button>


                                <button
                                    class="delete-comment"
                                    data-id="${comment.id}"
                                    title="Xóa"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>
                            `
                            :
                            ""
                        }

                    </div>


                    <div class="comment-content">

                        ${escapeHtml(
                            comment.content
                        )}

                    </div>

                </div>

            </div>

        `;

    });


    return `

        <article
            class="post-card"
            data-post-id="${post.id}"
        >


            <!-- POST HEADER -->

            <div class="post-header">

                <img
                    src="${escapeHtml(
                        post.avatar ||
                        "images/avatar.png"
                    )}"
                    class="post-avatar"
                >


                <div class="post-user">

                    <strong>
                        ${escapeHtml(post.username)}
                    </strong>

                    <span>
                        ${formatDate(post.created_at)}
                    </span>

                </div>


                ${
                    isOwner
                    ?
                    `
                    <div class="post-menu">

                        <button
                            class="edit-post"
                            title="Sửa bài"
                        >

                            <i class="fa-solid fa-pen"></i>

                        </button>


                        <button
                            class="delete-post"
                            title="Xóa bài"
                        >

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </div>
                    `
                    :
                    ""
                }

            </div>


            <!-- TITLE -->

            <h2 class="post-title">

                ${escapeHtml(post.title)}

            </h2>


            <!-- CONTENT -->

            <p class="post-description">

                ${escapeHtml(post.content)}

            </p>


            ${
                post.image
                ?
                `
                <img
                    src="${escapeHtml(post.image)}"
                    class="post-image"
                >
                `
                :
                ""
            }


            <!-- FOOTER -->

            <div class="post-footer">


                <div class="post-tags">

                    ${tags}

                </div>


                <div class="post-stats">


                    <button
                        class="
                            like-btn
                            ${post.liked ? "liked" : ""}
                        "
                        data-id="${post.id}"
                    >

                        <i
                            class="
                                fa-${
                                    post.liked
                                    ? "solid"
                                    : "regular"
                                }
                            fa-heart
                        "
                        ></i>

                        ${post.like_count}

                    </button>


                    <span>

                        <i class="fa-regular fa-comment"></i>

                        ${post.comment_count}

                    </span>

                </div>

            </div>


            <!-- COMMENTS -->

            <div class="comments-area">

                ${comments}


                <form
                    class="comment-form"
                    data-post-id="${post.id}"
                >

                    <input
                        type="text"
                        class="comment-input"
                        placeholder="Viết comment..."
                        required
                    >


                    <button
                        type="submit"
                        class="comment-submit"
                    >

                        Gửi

                    </button>

                </form>

            </div>


        </article>

    `;

}


// =================================================
// ATTACH EVENTS
// =================================================

function attachEvents() {


    // =============================================
    // LIKE
    // =============================================

    document
    .querySelectorAll(".like-btn")
    .forEach(button => {

        button.addEventListener(
            "click",
            async function() {

                const form =
                    new FormData();

                form.append(
                    "action",
                    "toggle_like"
                );

                form.append(
                    "post_id",
                    this.dataset.id
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: form
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // ADD COMMENT
    // =============================================

    document
    .querySelectorAll(".comment-form")
    .forEach(form => {

        form.addEventListener(
            "submit",
            async function(e) {

                e.preventDefault();


                const input =
                    this.querySelector(
                        ".comment-input"
                    );


                const content =
                    input.value.trim();


                if (!content) {
                    return;
                }


                const data =
                    new FormData();


                data.append(
                    "action",
                    "add_comment"
                );


                data.append(
                    "post_id",
                    this.dataset.postId
                );


                data.append(
                    "content",
                    content
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: data
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    input.value = "";

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // DELETE COMMENT
    // =============================================

    document
    .querySelectorAll(".delete-comment")
    .forEach(button => {

        button.addEventListener(
            "click",
            async function() {

                if (
                    !confirm(
                        "Bạn có chắc muốn xóa comment này?"
                    )
                ) {
                    return;
                }


                const data =
                    new FormData();


                data.append(
                    "action",
                    "delete_comment"
                );


                data.append(
                    "comment_id",
                    this.dataset.id
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: data
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // EDIT COMMENT
    // =============================================

    document
    .querySelectorAll(".edit-comment")
    .forEach(button => {

        button.addEventListener(
            "click",
            async function() {

                const newContent =
                    prompt(
                        "Nhập nội dung comment mới:"
                    );


                if (
                    newContent === null ||
                    newContent.trim() === ""
                ) {
                    return;
                }


                const data =
                    new FormData();


                data.append(
                    "action",
                    "update_comment"
                );


                data.append(
                    "comment_id",
                    this.dataset.id
                );


                data.append(
                    "content",
                    newContent.trim()
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: data
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // DELETE POST
    // =============================================

    document
    .querySelectorAll(".delete-post")
    .forEach(button => {

        button.addEventListener(
            "click",
            async function() {

                if (
                    !confirm(
                        "Bạn có chắc muốn xóa bài viết này?"
                    )
                ) {
                    return;
                }


                const card =
                    this.closest(".post-card");


                const postId =
                    card.dataset.postId;


                const data =
                    new FormData();


                data.append(
                    "action",
                    "delete_post"
                );


                data.append(
                    "post_id",
                    postId
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: data
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // EDIT POST
    // =============================================

    document
    .querySelectorAll(".edit-post")
    .forEach(button => {

        button.addEventListener(
            "click",
            async function() {

                const card =
                    this.closest(".post-card");


                const postId =
                    card.dataset.postId;


                const title =
                    prompt(
                        "Nhập tiêu đề mới:",
                        card
                        .querySelector(".post-title")
                        .innerText
                    );


                if (
                    title === null ||
                    title.trim() === ""
                ) {
                    return;
                }


                const content =
                    prompt(
                        "Nhập nội dung mới:",
                        card
                        .querySelector(".post-description")
                        .innerText
                    );


                if (
                    content === null ||
                    content.trim() === ""
                ) {
                    return;
                }


                const hashtags =
                    prompt(
                        "Nhập hashtag mới, ví dụ #NTTU #CNTT:"
                    );


                if (
                    hashtags === null ||
                    hashtags.trim() === ""
                ) {
                    return;
                }


                const data =
                    new FormData();


                data.append(
                    "action",
                    "update_post"
                );


                data.append(
                    "post_id",
                    postId
                );


                data.append(
                    "title",
                    title.trim()
                );


                data.append(
                    "content",
                    content.trim()
                );


                data.append(
                    "hashtags",
                    hashtags.trim()
                );


                const response =
                    await fetch(
                        "community_api.php",
                        {
                            method: "POST",
                            body: data
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    loadPosts();

                } else {

                    alert(result.message);

                }

            }
        );

    });


    // =============================================
    // HASHTAG
    // =============================================

    document
    .querySelectorAll(".post-tag")
    .forEach(tag => {

        tag.addEventListener(
            "click",
            function() {

                document.getElementById(
                    "hashtag-search"
                ).value =
                    "#" +
                    this.dataset.tag;


                loadPosts();

            }
        );

    });

}


// =================================================
// CREATE POST
// =================================================

document
.getElementById("create-post-form")
.addEventListener(
    "submit",
    async function(e) {

        e.preventDefault();


        const data =
            new FormData(this);


        data.append(
            "action",
            "create_post"
        );


        const response =
            await fetch(
                "community_api.php",
                {
                    method: "POST",
                    body: data
                }
            );


        const result =
            await response.json();


        if (result.success) {

            alert("Đăng bài thành công!");

            this.reset();

            document.getElementById(
                "image-preview"
            ).style.display =
                "none";


            loadPosts();

        } else {

            alert(result.message);

        }

    }
);


// =================================================
// IMAGE PREVIEW
// =================================================

document
.getElementById("post-image")
.addEventListener(
    "change",
    function() {

        const file =
            this.files[0];


        if (!file) {
            return;
        }


        const preview =
            document.getElementById(
                "image-preview"
            );


        preview.src =
            URL.createObjectURL(file);


        preview.style.display =
            "block";

    }
);


// =================================================
// SEARCH
// =================================================

let searchTimer;


document
.getElementById("hashtag-search")
.addEventListener(
    "input",
    function() {

        clearTimeout(searchTimer);


        searchTimer =
            setTimeout(
                loadPosts,
                300
            );

    }
);


// =================================================
// FILTER
// =================================================

document
.querySelectorAll(".filter-btn")
.forEach(button => {

    button.addEventListener(
        "click",
        function() {

            document
            .querySelectorAll(".filter-btn")
            .forEach(btn =>
                btn.classList.remove(
                    "active"
                )
            );


            this.classList.add(
                "active"
            );


            currentSort =
                this.dataset.sort;


            loadPosts();

        }
    );

});


loadPosts();

</script>

</body>

</html>