<?php
session_start();
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
            color: #777;
        }

        .like-btn {
            border: none;
            background: transparent;
            cursor: default;
            color: #777;
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
            background: #f4f5f7;
            border-radius: 8px;
            padding: 8px 12px;
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

        .login-notice {
            background: #eef2ff;
            color: #3564ff;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
            max-width: 850px;
            font-size: 13px;
        }

    </style>

</head>


<body>

<div class="page">


    <!-- HEADER -->

    <header class="header">

        <div class="logo-area">
            <img src="images/logo.png">
        </div>


        <div class="header-right">

            <a
                href="login.php"
                class="header-link"
            >
                <i class="fa-solid fa-lock"></i>
                <span>Login</span>
            </a>

            <a
                href="login.php"
                class="header-link"
            >
                <i class="fa-solid fa-user-plus"></i>
                <span>Sign up</span>
            </a>

        </div>

    </header>


    <div class="main">


        <!-- SIDEBAR -->

        <aside class="sidebar">

            <nav class="menu">

                <a href="index.html" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>

                <a href="map.html" class="menu-item">
                    <i class="fa-solid fa-map"></i>
                    <span>Bản đồ trường</span>
                </a>

                <a href="login.php" class="menu-item">
                    <i class="fa-solid fa-user"></i>
                    <span>Accounts</span>
                </a>

                <a href="course.html" class="menu-item">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Môn học</span>
                </a>

                <a href="community.php" class="menu-item active">
                    <i class="fa-solid fa-users"></i>
                    <span>Cộng đồng</span>
                </a>
                            
                <a href="ranking_results.php" class="menu-item">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Xếp hạng</span>

                </a>

            </nav>

        </aside>


        <!-- CONTENT -->

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


            <!-- NOTICE -->

            <div class="login-notice">

                <i class="fa-solid fa-circle-info"></i>

                Bạn đang xem Community với tư cách khách.
                <a href="login.php">
                    Đăng nhập
                </a>
                để đăng bài, like và comment.

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

let currentSort = "new";


function escapeHtml(text) {

    const div = document.createElement("div");

    div.textContent = text ?? "";

    return div.innerHTML;
}


function formatDate(date) {

    return new Date(date).toLocaleString("vi-VN");

}


async function loadPosts() {

    const search =
        document.getElementById("hashtag-search").value.trim();


    const url =
        "community_api.php?sort=" +
        encodeURIComponent(currentSort) +
        "&search=" +
        encodeURIComponent(search);


    const response = await fetch(url);

    const result = await response.json();


    const container =
        document.getElementById("posts-container");


    if (!result.success) {

        container.innerHTML =
            "<p>Không thể tải bài viết.</p>";

        return;
    }


    const posts = result.data;


    if (posts.length === 0) {

        container.innerHTML =
            "<p>Không tìm thấy bài viết nào.</p>";

        return;
    }


    container.innerHTML = "";


    posts.forEach(post => {

        let tags = "";

        post.tags.forEach(tag => {

            tags += `
                <span
                    class="post-tag"
                    onclick="searchTag('${escapeHtml(tag)}')"
                >
                    #${escapeHtml(tag)}
                </span>
            `;
        });


        let comments = "";


        post.comments.forEach(comment => {

            const avatar =
                comment.avatar ||
                "images/avatar.png";


            comments += `

                <div class="comment-item">

                    <img
                        src="${escapeHtml(avatar)}"
                        class="comment-avatar"
                    >

                    <div class="comment-body">

                        <div class="comment-name">
                            ${escapeHtml(comment.username)}
                        </div>

                        <div class="comment-content">
                            ${escapeHtml(comment.content)}
                        </div>

                    </div>

                </div>

            `;
        });


        container.innerHTML += `

            <article class="post-card">

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

                </div>


                <h2 class="post-title">
                    ${escapeHtml(post.title)}
                </h2>


                <p class="post-description">
                    ${escapeHtml(post.content)}
                </p>


                ${
                    post.image
                    ?
                    `<img
                        src="${escapeHtml(post.image)}"
                        class="post-image"
                    >`
                    :
                    ""
                }


                <div class="post-footer">

                    <div class="post-tags">
                        ${tags}
                    </div>

                    <div class="post-stats">

                        <span>
                            <i class="fa-regular fa-comment"></i>
                            ${post.comment_count}
                        </span>

                        <span>
                            <i class="fa-regular fa-heart"></i>
                            ${post.like_count}
                        </span>

                    </div>

                </div>


                ${
                    post.comments.length > 0
                    ?
                    `<div class="comments-area">
                        ${comments}
                    </div>`
                    :
                    ""
                }

            </article>

        `;

    });

}


function searchTag(tag) {

    document.getElementById(
        "hashtag-search"
    ).value = "#" + tag;

    loadPosts();

}


// =============================================
// FILTER
// =============================================

document.querySelectorAll(".filter-btn")
.forEach(button => {

    button.addEventListener("click", function() {

        document.querySelectorAll(".filter-btn")
        .forEach(btn =>
            btn.classList.remove("active")
        );

        this.classList.add("active");

        currentSort =
            this.dataset.sort;

        loadPosts();

    });

});


// =============================================
// SEARCH
// =============================================

let searchTimer;

document.getElementById(
    "hashtag-search"
).addEventListener("input", function() {

    clearTimeout(searchTimer);

    searchTimer = setTimeout(
        loadPosts,
        300
    );

});


loadPosts();

</script>

</body>
</html>
