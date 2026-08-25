<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NTTU - Home</title>

    <link rel="stylesheet" href="indexStyle.css">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >
</head>

<body>

<div class="page">

    <!-- ================= HEADER ================= -->
    <header class="header">

        <!-- Logo -->
        <div class="logo-area">
            <img src="images/logo.png" alt="NTTU Logo">
        </div>

        <!-- Header right -->
        <div class="header-right">

            <!-- Search -->
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    placeholder="Search for something"
                >
            </div>

            <!-- Login -->
            <a href="login.html" class="header-link">
                <i class="fa-solid fa-lock"></i>
                <span>login</span>
            </a>

            <!-- Sign Up -->
            <a href="login.html" class="header-link">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Sign Up</span>
            </a>

        </div>
    </header>


    <!-- ================= MAIN ================= -->
    <div class="main">

        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">

            <nav class="menu">

                <a href="#" class="menu-item active">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>

                <a href="map.html" class="menu-item">
                    <i class="fa-solid fa-map></i>
                    <span>Bản đồ trường</span>
                </a>

                <a href="login.html" class="menu-item">
                    <i class="fa-solid fa-user"></i>
                    <span>Accounts</span>
                </a>

                <a href="course.html" class="menu-item">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Môn học</span>
                </a>

                <a href="community.html" class="menu-item">
                    <i class="fa-solid fa-users"></i>
                    <span>Cộng đồng</span>
                </a>

                <a href="#" class="menu-item">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Xếp hạng</span>
                </a>

                <a href="#" class="menu-item">
                    <i class="fa-solid fa-gear"></i>
                    <span>Setting</span>
                </a>

            </nav>

        </aside>


        <!-- ================= CONTENT ================= -->
        <main class="content">

            <!-- Banner -->
            <section class="banner">
                <img
                    src="images/banner.webp"
                    alt="Điểm chuẩn trúng tuyển"
                >
            </section>


            <!-- News -->
            <section class="news-container">

                <!-- ===== MAIN NEWS ===== -->
                <article class="main-news">

                    <img
                        src="images/xettuyen.webp"
                        alt="Xét tuyển bổ sung"
                    >

                    <h2>
                        Thêm cơ hội học tại Trường ĐH Nguyễn Tất Thành:
                        Đăng ký xét tuyển bổ sung từ ngày 13/8/2026
                    </h2>

                    <p class="description">
                        NTTU – Theo thông tin từ Hội đồng tuyển sinh Trường
                        ĐH Nguyễn Tất Thành, Nhà trường sẽ tiến hành xét tuyển
                        bổ sung các chương trình đào tạo...
                    </p>

                    <span class="date">
                        11/08/2026
                    </span>

                </article>


                <!-- ===== SIDE NEWS ===== -->
                <div class="side-news">

                    <!-- News 1 -->
                    <article class="news-item">

                        <img
                            src="images/mnong.webp"
                            alt=""
                        >

                        <div class="news-info">

                            <h3>
                                Từ buôn làng Tây Nguyên đến giảng đường NTTU:
                                Hành trình rực rỡ của cô gái "M'nông"
                            </h3>

                            <span class="date">
                                18/08/2026
                            </span>

                        </div>

                    </article>


                    <!-- News 2 -->
                    <article class="news-item">

                        <img
                            src="images/sinhdoi.webp"
                            alt=""
                        >

                        <div class="news-info">

                            <h3>
                                Cùng lăn lên trong vòng tay mẹ và bà,
                                hai anh em sinh đôi cùng cho NTTU bắt đầu
                                hành trình mới
                            </h3>

                            <span class="date">
                                18/08/2026
                            </span>

                        </div>

                    </article>


                    <!-- News 3 -->
                    <article class="news-item">

                        <img
                            src="images/hoithao.webp"
                            alt=""
                        >

                        <div class="news-info">

                            <h3>
                                Hội thảo kết nối cựu sinh viên
                                "Những sắc màu thực tiễn ngành Kỹ thuật xét nghiệm"
                            </h3>

                            <span class="date">
                                17/08/2026
                            </span>

                        </div>

                    </article>

                </div>

            </section>

        </main>

    </div>

</div>

</body>
</html>