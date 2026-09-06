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


/* =========================================================
   ADMIN NOTICE
========================================================= */

.admin-notice {

    max-width: 850px;

    background: #eef2ff;

    border: 1px solid #d8e0ff;

    color: #3564ff;

    border-radius: 8px;

    padding: 12px 15px;

    margin-bottom: 20px;

    font-size: 13px;

}


.admin-notice i {

    margin-right: 6px;

}


/* =========================================================
   CREATE POST
========================================================= */

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


/* =========================================================
   FILTER
========================================================= */

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


/* =========================================================
   POSTS
========================================================= */

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


/* =========================================================
   POST ACTION
========================================================= */

.post-menu {

    margin-left: auto;

    display: flex;

    gap: 5px;

}


.post-menu button {

    border: none;

    background: transparent;

    cursor: pointer;

    color: #777;

    padding: 5px;

}


.post-menu button:hover {

    color: #3564ff;

}


/* =========================================================
   POST CONTENT
========================================================= */

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


/* =========================================================
   FOOTER
========================================================= */

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


/* =========================================================
   COMMENTS
========================================================= */

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


.comment-actions button {

    border: none;

    background: transparent;

    cursor: pointer;

    color: #777;

    padding: 5px;

}


.comment-actions button:hover {

    color: #3564ff;

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


<!-- =====================================================
     HEADER
====================================================== -->

<header class="header">


    <div class="logo-area">

        <img
            src="images/logo.png"
            alt="NTTU"
        >

    </div>


    <div class="header-right">

        <a
            href="admin_settings.php"
            class="user-avatar-link"
        >

            <img
                src="<?= htmlspecialchars($avatar) ?>"
                alt="Admin"
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


<!-- =====================================================
     MAIN
====================================================== -->

<div class="main">


<!-- =====================================================
     SIDEBAR ADMIN
====================================================== -->

<aside class="sidebar">


<nav class="menu">


    <!-- HOME -->

    <a
        href="admin.php"
        class="menu-item"
    >

        <i class="fa-solid fa-house"></i>

        <span>Home</span>

    </a>


    <!-- MAP -->

    <a
        href="map_users.php"
        class="menu-item"
    >

        <i class="fa-solid fa-map"></i>

        <span>Bản đồ trường</span>

    </a>


    <!-- ACCOUNTS -->

    <a
        href="admin_settings.php"
        class="menu-item"
    >

        <i class="fa-solid fa-user"></i>

        <span>Accounts</span>

    </a>


    <!-- COURSE -->

    <a
        href="course_users.php"
        class="menu-item"
    >

        <i class="fa-solid fa-book-open"></i>

        <span>Môn học</span>

    </a>


    <!-- COMMUNITY -->

    <a
        href="community_admin.php"
        class="menu-item active"
    >

        <i class="fa-solid fa-users"></i>

        <span>Cộng đồng</span>

    </a>


    <!-- RANKING -->

    <a
        href="ranking_results.php"
        class="menu-item"
    >

        <i class="fa-solid fa-ranking-star"></i>

        <span>Xếp hạng</span>

    </a>


    <!-- SETTING -->

    <a
        href="admin_settings.php"
        class="menu-item"
    >

        <i class="fa-solid fa-gear"></i>

        <span>Setting</span>

    </a>


</nav>


</aside>


<!-- =====================================================
     COMMUNITY CONTENT
====================================================== -->

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


<!-- ADMIN NOTICE -->

<div class="admin-notice">

    <i class="fa-solid fa-shield-halved"></i>

    <strong>Chế độ quản trị:</strong>

    Bạn có quyền sửa và xóa tất cả bài đăng và comment của người dùng.

</div>


<!-- =====================================================
     CREATE POST
====================================================== -->

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


<!-- =====================================================
     FILTER
====================================================== -->

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


<!-- POSTS -->

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


/* =========================================================
   ADMIN
========================================================= */

const CURRENT_USER_ID =
    <?= (int)$userId ?>;


/*
    Vì đây là community_admin.php nên luôn là ADMIN.
*/

const IS_ADMIN = true;


let currentSort = "new";


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(text) {

    const div =
        document.createElement("div");

    div.textContent =
        text ?? "";

    return div.innerHTML;

}


/* =========================================================
   DATE
========================================================= */

function formatDate(date) {

    return new Date(date)
        .toLocaleString("vi-VN");

}


/* =========================================================
   LOAD POSTS
========================================================= */

async function loadPosts() {


    try {


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


        if (
            !posts ||
            posts.length === 0
        ) {

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


    } catch (error) {


        console.error(error);


        document
        .getElementById("posts-container")
        .innerHTML =
            "<p>Không thể kết nối đến hệ thống.</p>";


    }

}


/* =========================================================
   CREATE POST HTML
========================================================= */

function createPostHtml(post) {


    const isOwner =
        Number(post.user_id) ===
        CURRENT_USER_ID;


    /*
        ADMIN CÓ QUYỀN QUẢN LÝ
        TẤT CẢ BÀI VIẾT.
    */

    const canManagePost =
        isOwner || IS_ADMIN;


    /* =====================================================
       TAGS
    ===================================================== */

    let tags = "";


    if (
        Array.isArray(post.tags)
    ) {

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

    }


    /* =====================================================
       COMMENTS
    ===================================================== */

    let comments = "";


    if (
        Array.isArray(post.comments)
    ) {


        post.comments.forEach(comment => {


            const isCommentOwner =
                Number(comment.user_id) ===
                CURRENT_USER_ID;


            /*ADMIN CÓ QUYỀN QUẢN LÝ TẤT CẢ COMMENT.*/

            const canManageComment =
                isCommentOwner ||
                IS_ADMIN;


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
                                canManageComment
                                ?

                                `

                                <div class="comment-actions">


                                    <button
                                        class="edit-comment"
                                        data-id="${comment.id}"
                                        title="Sửa comment"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </button>


                                    <button
                                        class="delete-comment"
                                        data-id="${comment.id}"
                                        title="Xóa comment"
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

    }


    /* =====================================================
       POST
    ===================================================== */

    return `

        <article
            class="post-card"
            data-post-id="${post.id}"
        >


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

                        ${escapeHtml(
                            post.username
                        )}

                    </strong>


                    <span>

                        ${formatDate(
                            post.created_at
                        )}

                    </span>


                </div>


                ${
                    canManagePost
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


            <h2 class="post-title">

                ${escapeHtml(
                    post.title
                )}

            </h2>


            <p class="post-description">

                ${escapeHtml(
                    post.content
                )}

            </p>


            ${
                post.image
                ?

                `

                <img
                    src="${escapeHtml(
                        post.image
                    )}"
                    class="post-image"
                >

                `

                :

                ""

            }


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


/* =========================================================
   API HELPER
========================================================= */

async function sendApi(data) {


    try {


        const response =
            await fetch(
                "community_api.php",
                {
                    method: "POST",
                    body: data
                }
            );


        return await response.json();


    } catch (error) {


        console.error(error);


        return {

            success: false,

            message:
                "Không thể kết nối đến máy chủ."

        };

    }

}


/* =========================================================
   ATTACH EVENTS
========================================================= */

function attachEvents() {


    /* =====================================================
       LIKE
    ===================================================== */

    document
    .querySelectorAll(".like-btn")
    .forEach(button => {


        button.addEventListener(
            "click",
            async function() {


                const data =
                    new FormData();


                data.append(
                    "action",
                    "toggle_like"
                );


                data.append(
                    "post_id",
                    this.dataset.id
                );


                const result =
                    await sendApi(data);


                if (result.success) {

                    loadPosts();

                } else {

                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       ADD COMMENT
    ===================================================== */

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


                const result =
                    await sendApi(data);


                if (result.success) {


                    input.value = "";


                    loadPosts();


                } else {


                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       DELETE COMMENT
    ===================================================== */

    document
    .querySelectorAll(".delete-comment")
    .forEach(button => {


        button.addEventListener(
            "click",
            async function() {


                if (
                    !confirm(
                        "Admin: Bạn có chắc muốn xóa comment này?"
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


                const result =
                    await sendApi(data);


                if (result.success) {


                    loadPosts();


                } else {


                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       EDIT COMMENT
    ===================================================== */

    document
    .querySelectorAll(".edit-comment")
    .forEach(button => {


        button.addEventListener(
            "click",
            async function() {


                const commentItem =
                    this.closest(
                        ".comment-item"
                    );


                const currentContent =
                    commentItem
                    .querySelector(
                        ".comment-content"
                    )
                    .innerText;


                const newContent =
                    prompt(
                        "Nhập nội dung comment mới:",
                        currentContent
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


                const result =
                    await sendApi(data);


                if (result.success) {


                    loadPosts();


                } else {


                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       DELETE POST
    ===================================================== */

    document
    .querySelectorAll(".delete-post")
    .forEach(button => {


        button.addEventListener(
            "click",
            async function() {


                if (
                    !confirm(
                        "ADMIN: Bạn có chắc muốn xóa bài viết này?"
                    )
                ) {

                    return;

                }


                const card =
                    this.closest(
                        ".post-card"
                    );


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


                const result =
                    await sendApi(data);


                if (result.success) {


                    loadPosts();


                } else {


                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       EDIT POST
    ===================================================== */

    document
    .querySelectorAll(".edit-post")
    .forEach(button => {


        button.addEventListener(
            "click",
            async function() {


                const card =
                    this.closest(
                        ".post-card"
                    );


                const postId =
                    card.dataset.postId;


                const oldTitle =
                    card
                    .querySelector(
                        ".post-title"
                    )
                    .innerText;


                const oldContent =
                    card
                    .querySelector(
                        ".post-description"
                    )
                    .innerText;


                const title =
                    prompt(
                        "ADMIN - Nhập tiêu đề mới:",
                        oldTitle
                    );


                if (
                    title === null ||
                    title.trim() === ""
                ) {

                    return;

                }


                const content =
                    prompt(
                        "ADMIN - Nhập nội dung mới:",
                        oldContent
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


                const result =
                    await sendApi(data);


                if (result.success) {


                    loadPosts();


                } else {


                    alert(
                        result.message
                    );

                }

            }
        );

    });


    /* =====================================================
       HASHTAG CLICK
    ===================================================== */

    document
    .querySelectorAll(".post-tag")
    .forEach(tag => {


        tag.addEventListener(
            "click",
            function() {


                document
                .getElementById(
                    "hashtag-search"
                )
                .value =
                    "#" +
                    this.dataset.tag;


                loadPosts();

            }
        );

    });

}


/* =========================================================
   CREATE POST
========================================================= */

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


        const result =
            await sendApi(data);


        if (result.success) {


            alert(
                "Đăng bài thành công!"
            );


            this.reset();


            document
            .getElementById(
                "image-preview"
            )
            .style.display =
                "none";


            loadPosts();


        } else {


            alert(
                result.message
            );

        }

    }
);


/* =========================================================
   IMAGE PREVIEW
========================================================= */

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


/* =========================================================
   SEARCH
========================================================= */

let searchTimer;

document
.getElementById("hashtag-search")
.addEventListener(
    "input",
    function() {


        clearTimeout(
            searchTimer
        );


        searchTimer =
            setTimeout(
                loadPosts,
                300
            );

    }
);

/* =========================================================
   FILTER
========================================================= */

document
.querySelectorAll(".filter-btn")
.forEach(button => {


    button.addEventListener(
        "click",
        function() {


            document
            .querySelectorAll(
                ".filter-btn"
            )
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


/* =========================================================
   LOAD
========================================================= */

loadPosts();


</script>

</body>
</html>