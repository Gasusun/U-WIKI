-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 07, 2026 lúc 07:37 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `wikiknst`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `community_comments`
--

CREATE TABLE `community_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `community_comments`
--

INSERT INTO `community_comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'kiet', '2026-09-05 10:05:40', '2026-09-05 10:05:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `community_hashtags`
--

CREATE TABLE `community_hashtags` (
  `id` int(11) NOT NULL,
  `hashtag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `community_hashtags`
--

INSERT INTO `community_hashtags` (`id`, `hashtag`) VALUES
(1, 'troll');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `community_likes`
--

CREATE TABLE `community_likes` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `community_likes`
--

INSERT INTO `community_likes` (`post_id`, `user_id`, `created_at`) VALUES
(1, 3, '2026-09-05 10:05:34'),
(1, 4, '2026-09-05 10:06:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `title`, `content`, `image`, `views`, `created_at`, `updated_at`) VALUES
(1, 3, 'Thực sự tày??', 'Anh tôi góp gạch xây trường, anh bạn đập đá đi xa', 'uploads/community/post_3_1788535567_3fa8189f.png', 0, '2026-09-04 15:26:07', '2026-09-04 15:26:07'),
(2, 4, 'Thg này đc phép ko chat?', 'bruh', 'uploads/community/post_4_1788705885_52b5c8eb.gif', 0, '2026-09-06 14:44:45', '2026-09-06 14:44:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `community_post_hashtags`
--

CREATE TABLE `community_post_hashtags` (
  `post_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `community_post_hashtags`
--

INSERT INTO `community_post_hashtags` (`post_id`, `hashtag_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT 'fa-book',
  `risk_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `main_content` text DEFAULT NULL,
  `learning_method` text DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `textbook` text DEFAULT NULL,
  `references_text` text DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `name`, `category`, `icon`, `risk_level`, `main_content`, `learning_method`, `objectives`, `textbook`, `references_text`, `video_url`, `created_at`, `updated_at`) VALUES
(1, 'Dịch vụ Hành chính', 'Hành chính', 'fa-building', 2, 'Khái niệm và vai trò: Bản chất của dịch vụ hành chính công và trách nhiệm của cơ quan nhà nước.\nQuy trình cung ứng: Thủ tục tiếp nhận, xử lý và trả kết quả hồ sơ theo cơ chế \"một cửa\" và \"một cửa liên thông\".\nCải cách hành chính: Ứng dụng công nghệ thông tin, dịch vụ công trực tuyến và các công nghệ quản lý hành chính.\nĐánh giá chất lượng: Tiêu chí đo lường sự hài lòng của người dân và văn hóa giao tiếp công sở.', 'Gắn liền với thực tế: Tra cứu và trải nghiệm trực tiếp các quy trình trên Cổng dịch vụ công quốc gia.\nPhân tích tình huống: Nghiên cứu các case-study về cải cách thủ tục hành chính.\nCập nhật văn bản luật: Liên tục theo dõi các văn bản, nghị định mới nhất về thủ tục hành chính công.', 'Về kiến thức: Nắm vững lý luận và quy trình quản lý, cung ứng dịch vụ hành chính công.\nVề kỹ năng: Thành thạo các nghiệp vụ xử lý hồ sơ và khả năng xây dựng, cải tiến quy trình công vụ.\nVề thái độ: Hình thành tư duy \"lấy người dân làm trung tâm\" và ý thức trách nhiệm trong văn hóa phục vụ.', 'Giáo trình chuyên ngành: Có thể tham khảo trực tuyến bản xem trước của Giáo trình Dịch vụ công trên Studocu do PGS.TS. Bùi Huy Đặng Khắc Ánh biên soạn, tập trung sâu vào bản chất dịch vụ công và quản lý hành chính.', 'Tài liệu học phần: Các tài liệu về mô hình và pháp luật quản lý hành chính công; có thể tham khảo các tài liệu học phần Quản lý nhà nước về Dịch vụ công.', 'https://www.youtube.com/channel/UCqt6OQXO4KkOkUTF3SmIMw', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(2, 'Công nghệ thông tin', 'Công nghệ', 'fa-laptop-code', 2, 'Kiến thức nền tảng về hệ thống thông tin, phần cứng, phần mềm và dữ liệu.\nVai trò của công nghệ thông tin trong tổ chức và đời sống.\nCác xu hướng công nghệ và ứng dụng chuyển đổi số.', 'Học lý thuyết kết hợp bài tập thực hành.\nPhân tích tình huống thực tế và thực hiện các bài tập nhóm.', 'Nắm được kiến thức nền tảng về CNTT.\nBiết lựa chọn công cụ phù hợp để giải quyết bài toán thực tế.\nCó ý thức cập nhật công nghệ mới.', 'Giáo trình nhập môn Công nghệ thông tin.', 'Tài liệu học phần và tài liệu tham khảo của giảng viên.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(3, 'Tiếng Anh', 'Ngoại ngữ', 'fa-language', 1, 'Phát triển vốn từ vựng và ngữ pháp tiếng Anh.\nLuyện nghe, nói, đọc, viết trong các tình huống học tập và giao tiếp.', 'Luyện tập theo chủ đề.\nThực hành hội thoại và bài tập trực tuyến.\nÔn tập định kỳ.', 'Cải thiện khả năng giao tiếp bằng tiếng Anh.\nĐọc hiểu tài liệu cơ bản.\nViết các đoạn văn và email thông dụng.', 'English Grammar in Use và giáo trình tiếng Anh của học phần.', 'Tài liệu luyện nghe, từ điển và học liệu trực tuyến.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(4, 'Kỹ năng mềm trong kỷ nguyên số - Cơ bản', 'Kỹ năng mềm', 'fa-brain', 2, 'Kỹ năng giao tiếp, làm việc nhóm và quản lý thời gian.\nTư duy số và khả năng thích nghi với môi trường học tập, làm việc hiện đại.', 'Học qua tình huống.\nThảo luận nhóm và trình bày.\nThực hành các bài tập kỹ năng.', 'Giao tiếp hiệu quả.\nLàm việc nhóm tốt.\nBiết lập kế hoạch và quản lý thời gian.', 'Giáo trình Kỹ năng mềm.', 'Tài liệu kỹ năng nghề nghiệp và tài liệu do giảng viên cung cấp.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(5, 'Toán cao cấp', 'Toán học', 'fa-calculator', 3, 'Các khái niệm về hàm số, đạo hàm, tích phân và đại số tuyến tính.\nỨng dụng toán học vào các bài toán chuyên ngành.', 'Học lý thuyết, làm bài tập và giải đề.\nThực hành theo nhóm với các bài toán ứng dụng.', 'Nắm vững kiến thức toán học nền tảng.\nGiải được các bài toán cơ bản và ứng dụng.', 'Giáo trình Toán cao cấp.', 'Bài giảng và ngân hàng bài tập của học phần.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(6, 'Lập trình', 'Công nghệ thông tin', 'fa-code', 3, 'Khái niệm lập trình, biến, kiểu dữ liệu, cấu trúc điều khiển và hàm.\nXây dựng các chương trình cơ bản bằng ngôn ngữ lập trình phù hợp.', 'Học qua ví dụ.\nThực hành viết chương trình.\nSửa lỗi và hoàn thiện bài tập.', 'Hiểu tư duy lập trình.\nViết được chương trình cơ bản.\nBiết kiểm thử và sửa lỗi.', 'Giáo trình nhập môn lập trình.', 'Tài liệu ngôn ngữ lập trình và tài liệu thực hành.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(7, 'Năng lực số và khai thác tài nguyên giáo dục mở', 'Kỹ năng số', 'fa-globe', 2, 'Kỹ năng tìm kiếm, đánh giá và sử dụng tài nguyên số.\nKhai thác học liệu mở và các công cụ hỗ trợ học tập.', 'Tìm kiếm thông tin theo nhiệm vụ.\nThực hành với các nền tảng học tập số.\nĐánh giá độ tin cậy của nguồn.', 'Khai thác tài nguyên giáo dục mở hiệu quả.\nCó kỹ năng an toàn và trách nhiệm khi sử dụng dữ liệu số.', 'Giáo trình Năng lực số.', 'Tài liệu học liệu mở và hướng dẫn sử dụng các nền tảng giáo dục.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(8, 'Toán rời rạc', 'Toán học', 'fa-puzzle-piece', 3, 'Tập hợp, logic, quan hệ, hàm, đồ thị và các cấu trúc rời rạc.\nCác phương pháp chứng minh và ứng dụng trong CNTT.', 'Học lý thuyết kết hợp giải bài tập.\nPhân tích bài toán và thảo luận cách giải.', 'Nắm được các cấu trúc toán học rời rạc.\nÁp dụng logic và đồ thị vào bài toán tin học.', 'Giáo trình Toán rời rạc.', 'Bài tập và tài liệu tham khảo của học phần.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(9, 'Pháp luật đại cương', 'Pháp luật', 'fa-scale-balanced', 2, 'Khái niệm cơ bản về nhà nước và pháp luật.\nHệ thống pháp luật Việt Nam và các quyền, nghĩa vụ cơ bản của công dân.', 'Học theo chuyên đề.\nPhân tích tình huống pháp lý thực tế.', 'Hiểu các quy định pháp luật cơ bản.\nBiết vận dụng kiến thức pháp luật vào đời sống.', 'Giáo trình Pháp luật đại cương.', 'Văn bản pháp luật và tài liệu học phần.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(10, 'Lập trình nâng cao', 'Công nghệ thông tin', 'fa-terminal', 4, 'Lập trình hướng đối tượng, cấu trúc dữ liệu và các kỹ thuật lập trình nâng cao.\nTối ưu chương trình và tổ chức mã nguồn.', 'Học qua dự án.\nThực hành bài toán nâng cao và review mã nguồn.', 'Viết chương trình có cấu trúc tốt.\nÁp dụng được kỹ thuật lập trình nâng cao.\nBiết đánh giá hiệu năng cơ bản.', 'Giáo trình Lập trình nâng cao.', 'Tài liệu ngôn ngữ lập trình và tài liệu dự án.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(11, 'Thiết kế và phát triển cơ sở dữ liệu', 'Cơ sở dữ liệu', 'fa-database', 4, 'Mô hình dữ liệu, thiết kế cơ sở dữ liệu quan hệ và SQL.\nChuẩn hóa dữ liệu, khóa, quan hệ và truy vấn.', 'Thiết kế ERD.\nThực hành SQL trên MySQL.\nPhân tích và tối ưu truy vấn cơ bản.', 'Thiết kế được cơ sở dữ liệu phù hợp.\nViết truy vấn SQL.\nBiết kiểm soát tính toàn vẹn dữ liệu.', 'Giáo trình Cơ sở dữ liệu.', 'MySQL documentation và tài liệu thực hành của học phần.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22'),
(12, 'Triết học Mác-Lê Nin', 'Triết học', 'fa-book', 2, 'Những nguyên lý cơ bản của triết học Mác-Lênin.\nThế giới quan, phương pháp luận và các quy luật cơ bản.', 'Học theo chuyên đề.\nThảo luận và liên hệ với các vấn đề thực tiễn.', 'Hiểu các khái niệm nền tảng.\nBiết vận dụng phương pháp luận vào học tập và thực tiễn.', 'Giáo trình Triết học Mác-Lênin.', 'Tài liệu học phần và tài liệu tham khảo của giảng viên.', 'https://www.youtube.com/', '2026-09-07 08:49:22', '2026-09-07 08:49:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ranking_items`
--

CREATE TABLE `ranking_items` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT 'images/avatar.png',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ranking_items`
--

INSERT INTO `ranking_items` (`id`, `name`, `image`, `active`, `created_at`) VALUES
(1, 'Thầy Nguyễn Văn A', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(2, 'Cô Trần Thị B', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(3, 'Thầy Lê Văn C', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(4, 'Cô Phạm Thị D', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(5, 'Thầy Hoàng Văn E', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(6, 'Cô Nguyễn Thị F', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(7, 'Thầy Phan Văn G', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(8, 'Cô Lê Thị H', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(9, 'Thầy Trần Văn I', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(10, 'Cô Hoàng Thị K', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(11, 'Thầy Nguyễn Văn L', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(12, 'Cô Phan Thị M', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(13, 'Thầy Đỗ Văn N', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(14, 'Cô Võ Thị O', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(15, 'Thầy Bùi Văn P', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(16, 'Cô Đặng Thị Q', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(17, 'Thầy Vũ Văn R', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(18, 'Cô Trương Thị S', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(19, 'Thầy Dương Văn T', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(20, 'Cô Lý Thị U', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(21, 'Thầy Nguyễn Văn V', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(22, 'Cô Trần Thị W', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(23, 'Thầy Phạm Văn X', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(24, 'Cô Lê Thị Y', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(25, 'Thầy Hồ Văn Z', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(26, 'Cô Mai Thị A1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(27, 'Thầy Cao Văn B1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(28, 'Cô Nguyễn Thị C1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(29, 'Thầy Đinh Văn D1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(30, 'Cô Phạm Thị E1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(31, 'Thầy Võ Văn F1', 'images/avatar.png', 1, '2026-09-05 15:33:31'),
(32, 'Cô Nguyễn Thị G1', 'images/avatar.png', 1, '2026-09-05 15:33:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ranking_sessions`
--

CREATE TABLE `ranking_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ranking_sessions`
--

INSERT INTO `ranking_sessions` (`id`, `user_id`, `status`, `created_at`, `completed_at`) VALUES
(1, 3, 'completed', '2026-09-05 15:59:34', '2026-09-05 23:29:45'),
(2, 4, 'completed', '2026-09-05 16:02:29', '2026-09-05 23:06:28'),
(3, 4, 'completed', '2026-09-05 16:06:48', '2026-09-06 20:29:24'),
(4, 3, 'active', '2026-09-05 16:32:23', NULL),
(5, 2, 'active', '2026-09-06 14:25:04', NULL),
(6, 4, 'active', '2026-09-06 15:27:43', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ranking_session_matches`
--

CREATE TABLE `ranking_session_matches` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `match_no` int(11) NOT NULL,
  `item_a_id` int(11) NOT NULL,
  `item_b_id` int(11) NOT NULL,
  `selected_item_id` int(11) DEFAULT NULL,
  `voted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ranking_session_matches`
--

INSERT INTO `ranking_session_matches` (`id`, `session_id`, `match_no`, `item_a_id`, `item_b_id`, `selected_item_id`, `voted_at`) VALUES
(1, 1, 1, 2, 14, 2, '2026-09-05 23:29:39'),
(2, 1, 2, 16, 8, 8, '2026-09-05 23:29:40'),
(3, 1, 3, 1, 17, 17, '2026-09-05 23:29:41'),
(4, 1, 4, 10, 27, 27, '2026-09-05 23:29:41'),
(5, 1, 5, 11, 23, 23, '2026-09-05 23:29:41'),
(6, 1, 6, 28, 31, 31, '2026-09-05 23:29:42'),
(7, 1, 7, 21, 6, 6, '2026-09-05 23:29:42'),
(8, 1, 8, 5, 15, 15, '2026-09-05 23:29:42'),
(9, 1, 9, 22, 25, 25, '2026-09-05 23:29:43'),
(10, 1, 10, 19, 3, 3, '2026-09-05 23:29:43'),
(11, 1, 11, 32, 4, 4, '2026-09-05 23:29:43'),
(12, 1, 12, 13, 18, 18, '2026-09-05 23:29:44'),
(13, 1, 13, 12, 30, 30, '2026-09-05 23:29:44'),
(14, 1, 14, 29, 20, 20, '2026-09-05 23:29:44'),
(15, 1, 15, 24, 7, 7, '2026-09-05 23:29:45'),
(16, 1, 16, 9, 26, 26, '2026-09-05 23:29:45'),
(17, 2, 1, 27, 11, 27, '2026-09-05 23:06:21'),
(18, 2, 2, 5, 8, 8, '2026-09-05 23:06:22'),
(19, 2, 3, 17, 24, 17, '2026-09-05 23:06:23'),
(20, 2, 4, 2, 7, 7, '2026-09-05 23:06:24'),
(21, 2, 5, 22, 29, 22, '2026-09-05 23:06:24'),
(22, 2, 6, 1, 6, 1, '2026-09-05 23:06:24'),
(23, 2, 7, 21, 14, 21, '2026-09-05 23:06:25'),
(24, 2, 8, 13, 32, 13, '2026-09-05 23:06:25'),
(25, 2, 9, 4, 19, 4, '2026-09-05 23:06:25'),
(26, 2, 10, 28, 12, 28, '2026-09-05 23:06:26'),
(27, 2, 11, 20, 18, 20, '2026-09-05 23:06:26'),
(28, 2, 12, 10, 30, 10, '2026-09-05 23:06:26'),
(29, 2, 13, 31, 16, 31, '2026-09-05 23:06:27'),
(30, 2, 14, 15, 25, 15, '2026-09-05 23:06:27'),
(31, 2, 15, 26, 23, 26, '2026-09-05 23:06:27'),
(32, 2, 16, 3, 9, 3, '2026-09-05 23:06:28'),
(33, 3, 1, 1, 21, 21, '2026-09-06 20:29:15'),
(34, 3, 2, 26, 3, 26, '2026-09-06 20:29:16'),
(35, 3, 3, 15, 16, 16, '2026-09-06 20:29:17'),
(36, 3, 4, 14, 18, 14, '2026-09-06 20:29:17'),
(37, 3, 5, 28, 29, 29, '2026-09-06 20:29:18'),
(38, 3, 6, 2, 17, 2, '2026-09-06 20:29:18'),
(39, 3, 7, 8, 32, 32, '2026-09-06 20:29:18'),
(40, 3, 8, 23, 11, 23, '2026-09-06 20:29:19'),
(41, 3, 9, 24, 30, 30, '2026-09-06 20:29:19'),
(42, 3, 10, 10, 22, 10, '2026-09-06 20:29:19'),
(43, 3, 11, 12, 13, 13, '2026-09-06 20:29:20'),
(44, 3, 12, 6, 7, 6, '2026-09-06 20:29:20'),
(45, 3, 13, 9, 31, 31, '2026-09-06 20:29:21'),
(46, 3, 14, 5, 4, 5, '2026-09-06 20:29:22'),
(47, 3, 15, 25, 19, 19, '2026-09-06 20:29:23'),
(48, 3, 16, 27, 20, 20, '2026-09-06 20:29:24'),
(49, 4, 1, 32, 8, 32, '2026-09-05 23:33:10'),
(50, 4, 2, 6, 21, 21, '2026-09-05 23:33:11'),
(51, 4, 3, 16, 12, 12, '2026-09-06 20:31:14'),
(52, 4, 4, 1, 3, 1, '2026-09-06 20:31:14'),
(53, 4, 5, 5, 31, 5, '2026-09-06 20:31:15'),
(54, 4, 6, 18, 20, NULL, NULL),
(55, 4, 7, 4, 9, NULL, NULL),
(56, 4, 8, 25, 22, NULL, NULL),
(57, 4, 9, 28, 2, NULL, NULL),
(58, 4, 10, 27, 10, NULL, NULL),
(59, 4, 11, 13, 7, NULL, NULL),
(60, 4, 12, 14, 26, NULL, NULL),
(61, 4, 13, 24, 30, NULL, NULL),
(62, 4, 14, 23, 29, NULL, NULL),
(63, 4, 15, 19, 15, NULL, NULL),
(64, 4, 16, 17, 11, NULL, NULL),
(65, 5, 1, 9, 3, NULL, NULL),
(66, 5, 2, 13, 21, NULL, NULL),
(67, 5, 3, 29, 19, NULL, NULL),
(68, 5, 4, 4, 27, NULL, NULL),
(69, 5, 5, 20, 30, NULL, NULL),
(70, 5, 6, 6, 26, NULL, NULL),
(71, 5, 7, 28, 11, NULL, NULL),
(72, 5, 8, 22, 17, NULL, NULL),
(73, 5, 9, 5, 12, NULL, NULL),
(74, 5, 10, 24, 15, NULL, NULL),
(75, 5, 11, 31, 25, NULL, NULL),
(76, 5, 12, 7, 1, NULL, NULL),
(77, 5, 13, 32, 8, NULL, NULL),
(78, 5, 14, 16, 10, NULL, NULL),
(79, 5, 15, 18, 14, NULL, NULL),
(80, 5, 16, 23, 2, NULL, NULL),
(81, 6, 1, 22, 17, NULL, NULL),
(82, 6, 2, 26, 15, NULL, NULL),
(83, 6, 3, 21, 11, NULL, NULL),
(84, 6, 4, 13, 29, NULL, NULL),
(85, 6, 5, 20, 27, NULL, NULL),
(86, 6, 6, 6, 24, NULL, NULL),
(87, 6, 7, 5, 25, NULL, NULL),
(88, 6, 8, 30, 9, NULL, NULL),
(89, 6, 9, 10, 12, NULL, NULL),
(90, 6, 10, 18, 19, NULL, NULL),
(91, 6, 11, 1, 28, NULL, NULL),
(92, 6, 12, 23, 16, NULL, NULL),
(93, 6, 13, 2, 4, NULL, NULL),
(94, 6, 14, 8, 3, NULL, NULL),
(95, 6, 15, 14, 7, NULL, NULL),
(96, 6, 16, 32, 31, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `password`, `role`) VALUES
(2, 'phi', 'phi@gmail.com', NULL, 'images/avatar.png', '$2y$10$2o2NJa9J.kZGL96EQdMdFOT/PDJ6RB9WoQsgXDZNwSq26ektIJzT2', 'admin'),
(3, 'kiet', 'kiet@gmail.com', '', 'uploads/avatars/avatar_3_1788506784.png', '$2y$10$1ASabQxYBlXhLNx65FR1G.heHV.6AqDPIyX0qqj5v.tEEPxiQ7PNe', 'user'),
(4, 'bd', 'bd@gmail.com', '', 'images/avatar.png', '$2y$10$VOwH0WiW80b7nT7NxvitCOcGB4tpQ2IeqyqDLuIip84zoL7y38z6O', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comments_post` (`post_id`),
  ADD KEY `idx_comments_user` (`user_id`);

--
-- Chỉ mục cho bảng `community_hashtags`
--
ALTER TABLE `community_hashtags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_hashtag` (`hashtag`);

--
-- Chỉ mục cho bảng `community_likes`
--
ALTER TABLE `community_likes`
  ADD PRIMARY KEY (`post_id`,`user_id`),
  ADD KEY `idx_likes_user` (`user_id`),
  ADD KEY `idx_likes_post` (`post_id`);

--
-- Chỉ mục cho bảng `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_user` (`user_id`),
  ADD KEY `idx_posts_created` (`created_at`);

--
-- Chỉ mục cho bảng `community_post_hashtags`
--
ALTER TABLE `community_post_hashtags`
  ADD PRIMARY KEY (`post_id`,`hashtag_id`),
  ADD KEY `idx_post_hashtag_post` (`post_id`),
  ADD KEY `idx_post_hashtag_hashtag` (`hashtag_id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ranking_items`
--
ALTER TABLE `ranking_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ranking_sessions`
--
ALTER TABLE `ranking_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ranking_session_user` (`user_id`);

--
-- Chỉ mục cho bảng `ranking_session_matches`
--
ALTER TABLE `ranking_session_matches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session_match` (`session_id`,`match_no`),
  ADD KEY `idx_match_session` (`session_id`),
  ADD KEY `idx_match_item_a` (`item_a_id`),
  ADD KEY `idx_match_item_b` (`item_b_id`),
  ADD KEY `idx_match_selected` (`selected_item_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `community_hashtags`
--
ALTER TABLE `community_hashtags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ranking_items`
--
ALTER TABLE `ranking_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `ranking_sessions`
--
ALTER TABLE `ranking_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `ranking_session_matches`
--
ALTER TABLE `ranking_session_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
