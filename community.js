document.addEventListener("DOMContentLoaded", () => {

    const postsContainer = document.getElementById("posts-container");

    const filterButtons = document.querySelectorAll(".filter-btn");


    // =========================
    // LOAD POSTS
    // =========================

    function loadPosts(sort = "new") {

        postsContainer.innerHTML = `
            <p class="loading">
                Đang tải bài viết...
            </p>
        `;


        fetch(`api/posts.php?sort=${sort}`)

            .then(response => {

                if (!response.ok) {
                    throw new Error("Không thể kết nối server");
                }

                return response.json();

            })

            .then(posts => {

                postsContainer.innerHTML = "";


                // Không có bài viết
                if (posts.length === 0) {

                    postsContainer.innerHTML = `
                        <p class="no-post">
                            Chưa có bài viết nào.
                        </p>
                    `;

                    return;
                }


                // Hiển thị từng bài
                posts.forEach(post => {

                    const article = document.createElement("article");

                    article.classList.add("post-card");


                    article.innerHTML = `

                        <!-- HEADER -->

                        <div class="post-header">

                            <img
                                src="images/${post.avatar || "default-avatar.png"}"
                                class="post-avatar"
                                alt="Avatar"
                            >

                            <div class="post-user">

                                <strong>
                                    ${post.username}
                                </strong>

                                <span>
                                    ${formatDate(post.created_at)}
                                </span>

                            </div>


                            <button
                                class="post-more"
                                data-id="${post.post_id}"
                            >

                                <i class="fa-solid fa-ellipsis-vertical"></i>

                            </button>

                        </div>


                        <!-- TITLE -->

                        <h2 class="post-title">
                            ${post.title}
                        </h2>


                        <!-- CONTENT -->

                        <p class="post-description">
                            ${post.content}
                        </p>


                        <!-- FOOTER -->

                        <div class="post-footer">


                            <!-- TAGS -->

                            <div class="post-tags">

                                ${createTags(post.tags)}

                            </div>


                            <!-- STATS -->

                            <div class="post-stats">

                                <span>

                                    <i class="fa-regular fa-eye"></i>

                                    ${post.views || 0}

                                </span>


                                <span>

                                    <i class="fa-regular fa-comment"></i>

                                    ${post.comments || 0}

                                </span>


                                <span>

                                    <i class="fa-solid fa-arrow-up"></i>

                                    ${post.votes || 0}

                                </span>

                            </div>

                        </div>

                    `;


                    postsContainer.appendChild(article);

                });

            })

            .catch(error => {

                console.error(error);

                postsContainer.innerHTML = `

                    <div class="error-message">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <p>
                            Không thể tải bài viết.
                        </p>

                    </div>

                `;

            });

    }



    // =========================
    // CREATE TAGS
    // =========================

    function createTags(tags) {

        if (!tags) {
            return "";
        }


        // Nếu PHP trả về mảng
        if (Array.isArray(tags)) {

            return tags.map(tag => {

                return `
                    <span>${tag}</span>
                `;

            }).join("");

        }


        return `
            <span>${tags}</span>
        `;

    }



    // =========================
    // FORMAT DATE
    // =========================

    function formatDate(dateString) {

        const date = new Date(dateString);

        const now = new Date();

        const diff =
            Math.floor(
                (now - date) / 1000
            );


        // Dưới 1 phút
        if (diff < 60) {

            return "Vừa xong";

        }


        // Dưới 1 giờ
        if (diff < 3600) {

            return `${Math.floor(diff / 60)} phút trước`;

        }


        // Dưới 1 ngày
        if (diff < 86400) {

            return `${Math.floor(diff / 3600)} giờ trước`;

        }


        // Dưới 7 ngày
        if (diff < 604800) {

            return `${Math.floor(diff / 86400)} ngày trước`;

        }


        // Cũ hơn
        return date.toLocaleDateString("vi-VN");

    }



    // =========================
    // FILTER BUTTON
    // =========================

    filterButtons.forEach(button => {

        button.addEventListener("click", () => {


            // Xóa active
            filterButtons.forEach(btn => {

                btn.classList.remove("active");

            });


            // Active button hiện tại
            button.classList.add("active");


            // Xác định filter
            let sort = "new";


            if (button.innerText.includes("Top")) {

                sort = "top";

            }

            else if (button.innerText.includes("Hot")) {

                sort = "hot";

            }

            else if (button.innerText.includes("Closed")) {

                sort = "closed";

            }


            loadPosts(sort);

        });

    });



    // =========================
    // LOAD FIRST TIME
    // =========================

    loadPosts("new");

});