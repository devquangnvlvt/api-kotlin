-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2026 at 09:35 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ahieu`
--

-- --------------------------------------------------------

--
-- Table structure for table `ad_reward_logs`
--

CREATE TABLE `ad_reward_logs` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lượt xem quảng cáo (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người xem quảng cáo, FK -> users.id',
  `reward_amount` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số tiền/xu thưởng nhận được sau khi xem quảng cáo',
  `watched_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm xem xong quảng cáo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID huy hiệu (khoá chính)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên huy hiệu',
  `icon_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL icon huy hiệu',
  `price` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = huy hiệu thành tích, không bán',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Đang bán/hiển thị trong Shop hay không',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo vật phẩm',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkin_logs`
--

CREATE TABLE `checkin_logs` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lượt điểm danh (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người điểm danh, FK -> users.id',
  `checkin_date` date NOT NULL COMMENT 'Ngày thực hiện điểm danh',
  `streak_day` tinyint UNSIGNED NOT NULL COMMENT 'Ngày thứ mấy trong chuỗi 7 ngày (1-7)',
  `reward_amount` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số tiền/xu thưởng nhận được khi điểm danh',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm ghi nhận bản ghi điểm danh'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bình luận (khoá chính)',
  `post_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết được bình luận, FK -> posts.id',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người bình luận, FK -> users.id',
  `parent_comment_id` bigint UNSIGNED DEFAULT NULL COMMENT 'NULL = bình luận gốc, có giá trị = reply',
  `content` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung văn bản bình luận',
  `sticker_id` bigint UNSIGNED DEFAULT NULL COMMENT 'FK -> stickers.id (thêm sau ở Module 5)',
  `likes_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số lượt thích bình luận, đồng bộ qua trigger',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm bình luận',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm chỉnh sửa bình luận gần nhất',
  `deleted_at` datetime DEFAULT NULL COMMENT 'Thời điểm xoá mềm bình luận (soft-delete)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lượt thích bình luận (khoá chính)',
  `comment_id` bigint UNSIGNED NOT NULL COMMENT 'ID bình luận được thích, FK -> comments.id',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người thực hiện thích, FK -> users.id',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thích bình luận'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_tasks`
--

CREATE TABLE `daily_tasks` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID nhiệm vụ (khoá chính)',
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhiệm vụ hiển thị cho user',
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả chi tiết nhiệm vụ',
  `task_type` enum('post','like','comment','follow','other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại hành động cần thực hiện để hoàn thành nhiệm vụ',
  `target_count` int UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Số lượng hành động cần để hoàn thành',
  `reward_amount` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số tiền/xu thưởng khi hoàn thành nhiệm vụ',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Nhiệm vụ có đang được kích hoạt hay không',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo nhiệm vụ',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật nhiệm vụ gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID quan hệ follow (khoá chính)',
  `follower_id` bigint UNSIGNED NOT NULL COMMENT 'Người thực hiện follow',
  `following_id` bigint UNSIGNED NOT NULL COMMENT 'Người được follow',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thực hiện follow'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `follows`
--
DELIMITER $$
CREATE TRIGGER `trg_follows_before_insert` BEFORE INSERT ON `follows` FOR EACH ROW BEGIN
    IF NEW.follower_id = NEW.following_id THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Không thể tự follow chính mình';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `frames`
--

CREATE TABLE `frames` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID khung viền (khoá chính)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên khung viền',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh khung viền',
  `price` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = miễn phí',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Đang bán/hiển thị trong Shop hay không',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo vật phẩm',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard_cache`
--

CREATE TABLE `leaderboard_cache` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bản ghi xếp hạng (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng được xếp hạng, FK -> users.id',
  `period_type` enum('week','month','all_time') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Chu kỳ tính xếp hạng: tuần, tháng hoặc toàn thời gian',
  `period_key` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'VD: 2026-W30, 2026-07, all',
  `total_earned` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Tổng số tiền/xu kiếm được trong chu kỳ',
  `rank_position` int UNSIGNED DEFAULT NULL COMMENT 'Vị trí xếp hạng hiện tại',
  `calculated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tính toán/cập nhật bảng xếp hạng gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID thông báo (khoá chính)',
  `recipient_id` bigint UNSIGNED NOT NULL COMMENT 'Người nhận thông báo',
  `actor_id` bigint UNSIGNED NOT NULL COMMENT 'Người thực hiện hành động',
  `type` enum('like','comment','follow','mention') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại thông báo',
  `target_type` enum('post','comment','user') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại đối tượng liên quan (NULL với type=follow)',
  `target_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID đối tượng liên quan, VD: post_id, comment_id',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Người nhận đã đọc thông báo hay chưa',
  `read_at` datetime DEFAULT NULL COMMENT 'Thời điểm người nhận đọc thông báo',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thông báo được tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 1, 'auth_token', '7e1e1f8848fd11a4cffe1aefcc6851390c49ffcd5e42b5ebb7c9a86978fb2376', '[\"*\"]', NULL, NULL, '2026-07-27 01:22:10', '2026-07-27 01:22:10'),
(3, 'App\\Models\\User', 1, 'auth_token', '4758f656acc4254cf7388f54be6646369182a2b3bf996296a0d00a2d3c3265e1', '[\"*\"]', NULL, NULL, '2026-07-27 01:25:18', '2026-07-27 01:25:18'),
(4, 'App\\Models\\User', 1, 'auth_token', '97d640e4681c08ed259da0de6e4069e6e7edf61eae013b8d504167b64fafeb50', '[\"*\"]', NULL, NULL, '2026-07-27 01:25:55', '2026-07-27 01:25:55'),
(5, 'App\\Models\\User', 1, 'auth_token', 'b2235650d4662248300c3ad69866dd0dfa4651e0d4dca215200c2bfbf543b5ab', '[\"*\"]', NULL, NULL, '2026-07-27 01:26:08', '2026-07-27 01:26:08'),
(6, 'App\\Models\\User', 1, 'auth_token', '7e463a80431214994315ab71376da771a04a42ef72651b829afa4788ea17d25a', '[\"*\"]', NULL, NULL, '2026-07-27 01:26:41', '2026-07-27 01:26:41'),
(7, 'App\\Models\\User', 1, 'auth_token', 'b1dfb27e40f42c3878403b133772bd754a2a98211e540aa0654f61b40408c48c', '[\"*\"]', NULL, NULL, '2026-07-27 01:27:22', '2026-07-27 01:27:22'),
(9, 'App\\Models\\User', 1, 'auth_token', '89973de079426e2c024e49ec8836a2a3053bf76a58454f1f09ac5f7a04cc3c7d', '[\"*\"]', NULL, NULL, '2026-07-27 02:19:28', '2026-07-27 02:19:28'),
(10, 'App\\Models\\User', 1, 'auth_token', '3efd7514b1168b67a75459b6fd409c82817fb7e230075c1a8461cba11b78f9b0', '[\"*\"]', NULL, NULL, '2026-07-27 02:24:00', '2026-07-27 02:24:00'),
(11, 'App\\Models\\User', 1, 'auth_token', '05b4ceb7e2b0659ccec65aac35fbde3142bb9a96b29200d240a0f466401e69bc', '[\"*\"]', NULL, NULL, '2026-07-27 02:25:07', '2026-07-27 02:25:07'),
(13, 'App\\Models\\User', 1, 'auth_token', 'a7eb9f7d84b81547c2fa7a47439b893a8a17f9fc37d4e0c514f19b9a620ad40e', '[\"*\"]', '2026-07-27 02:34:24', NULL, '2026-07-27 02:34:18', '2026-07-27 02:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người đăng bài, FK -> users.id',
  `character_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Nhân vật Maker đính kèm (nếu có), FK -> maker_characters.id',
  `caption` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung văn bản của bài viết',
  `status` enum('published','hidden','deleted','followers_only') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published' COMMENT 'Trạng thái hiển thị bài viết',
  `likes_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số lượt thích, đồng bộ qua trigger từ post_likes',
  `comments_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bình luận, đồng bộ qua trigger từ comments',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm đăng bài',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm chỉnh sửa gần nhất',
  `deleted_at` datetime DEFAULT NULL COMMENT 'Thời điểm xoá mềm bài viết (soft-delete)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID ảnh (khoá chính)',
  `post_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết chứa ảnh, FK -> posts.id',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh đính kèm bài viết',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thứ tự hiển thị ảnh trong bài viết',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thêm ảnh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lượt thích (khoá chính)',
  `post_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết được thích, FK -> posts.id',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người thực hiện thích, FK -> users.id',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thích bài viết'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_saves`
--

CREATE TABLE `post_saves` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID lượt lưu bài (khoá chính)',
  `post_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết được lưu, FK -> posts.id',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người lưu bài, FK -> users.id',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm lưu bài viết'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_purchases`
--

CREATE TABLE `shop_purchases` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID giao dịch mua hàng (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người mua, FK -> users.id',
  `item_type` enum('frame','badge','sticker_pack','avatar_template','background') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại vật phẩm được mua, xác định bảng tham chiếu của item_id',
  `item_id` bigint UNSIGNED NOT NULL COMMENT 'ID vật phẩm tương ứng theo item_type',
  `price_paid` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Giá thực tế đã thanh toán',
  `purchased_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm mua vật phẩm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stickers`
--

CREATE TABLE `stickers` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID sticker (khoá chính)',
  `pack_id` bigint UNSIGNED NOT NULL COMMENT 'ID gói sticker chứa ảnh này, FK -> sticker_packs.id',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh sticker',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thứ tự hiển thị sticker trong gói',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm thêm sticker'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sticker_packs`
--

CREATE TABLE `sticker_packs` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID gói sticker (khoá chính)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên gói sticker',
  `cover_image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL ảnh bìa đại diện gói sticker',
  `price` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Giá bán, 0 = miễn phí',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Đang bán/hiển thị trong Shop hay không',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo gói sticker',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng (khoá chính)',
  `google_uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UID định danh từ Google OAuth',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên đăng nhập/hiển thị duy nhất',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email tài khoản',
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Họ tên hiển thị',
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL ảnh đại diện',
  `bio` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiểu sử / mô tả ngắn về bản thân',
  `checkin_streak` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số ngày điểm danh liên tiếp hiện tại',
  `last_checkin_date` date DEFAULT NULL COMMENT 'Ngày điểm danh gần nhất',
  `posts_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bài viết published, đồng bộ qua trigger',
  `followers_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số người theo dõi, đồng bộ qua trigger',
  `following_count` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số người đang theo dõi, đồng bộ qua trigger',
  `status` enum('active','suspended','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Trạng thái tài khoản',
  `deleted_at` datetime DEFAULT NULL COMMENT 'Thời điểm xoá mềm tài khoản (soft-delete)',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'vai trò',
  `registration_source`varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' đăng kí ở đâu mạng xã hội',
  `active_frame_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Khung viền đang được chọn hiển thị, FK -> frames.id',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo tài khoản',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `google_uid`, `username`, `email`, `full_name`, `avatar_url`, `bio`, `checkin_streak`, `last_checkin_date`, `posts_count`, `followers_count`, `following_count`, `status`, `deleted_at`, `active_frame_id`, `created_at`, `updated_at`) VALUES
(1, '113186164704248682855', 'quangnv_ee3ed3', 'quangnv@lvtapp.com', 'Quang Nguyen Van', 'https://lh3.googleusercontent.com/a/ACg8ocKbaOx4Qw-1ypNYZMDS-rLotrf53Zm6mkdDebRNjCxirtGSiw=s96-c', NULL, 0, NULL, 0, 0, 0, 'active', NULL, NULL, '2026-07-27 07:56:52', '2026-07-27 08:40:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bản ghi sở hữu huy hiệu (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người sở hữu, FK -> users.id',
  `badge_id` bigint UNSIGNED NOT NULL COMMENT 'ID huy hiệu sở hữu, FK -> badges.id',
  `pinned_order` tinyint UNSIGNED DEFAULT NULL COMMENT 'NULL = không ghim, giá trị 1-3 = vị trí ghim',
  `acquired_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm đạt được/mua huy hiệu'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `user_daily_tasks`
--

CREATE TABLE `user_daily_tasks` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID tiến độ nhiệm vụ (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người thực hiện nhiệm vụ, FK -> users.id',
  `task_id` bigint UNSIGNED NOT NULL COMMENT 'ID nhiệm vụ tương ứng, FK -> daily_tasks.id',
  `task_date` date NOT NULL COMMENT 'Ngày áp dụng nhiệm vụ (nhiệm vụ reset theo ngày)',
  `status` enum('in_progress','completed','claimed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress' COMMENT 'Trạng thái tiến độ nhiệm vụ',
  `progress_current` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số lượng hành động đã thực hiện trong ngày',
  `completed_at` datetime DEFAULT NULL COMMENT 'Thời điểm hoàn thành nhiệm vụ',
  `claimed_at` datetime DEFAULT NULL COMMENT 'Thời điểm nhận thưởng nhiệm vụ',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm khởi tạo bản ghi tiến độ',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật tiến độ gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_frames`
--

CREATE TABLE `user_frames` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bản ghi sở hữu (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người sở hữu, FK -> users.id',
  `frame_id` bigint UNSIGNED NOT NULL COMMENT 'ID khung viền sở hữu, FK -> frames.id',
  `acquired_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm mua/sở hữu khung viền'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng, khoá chính đồng thời là FK -> users.id',
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vi' COMMENT 'Mã ngôn ngữ ứng dụng, VD: vi, en',
  `notify_like` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bật/tắt thông báo khi có lượt thích',
  `notify_comment` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bật/tắt thông báo khi có bình luận',
  `notify_follow` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bật/tắt thông báo khi có người theo dõi mới',
  `notify_mention` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bật/tắt thông báo khi được nhắc đến (mention)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo cấu hình',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật cấu hình gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sticker_packs`
--

CREATE TABLE `user_sticker_packs` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID bản ghi sở hữu gói sticker (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người sở hữu, FK -> users.id',
  `pack_id` bigint UNSIGNED NOT NULL COMMENT 'ID gói sticker sở hữu, FK -> sticker_packs.id',
  `acquired_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm mua gói sticker'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng, khoá chính đồng thời là FK -> users.id',
  `balance` bigint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Được đồng bộ tự động qua trigger từ wallet_transactions',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm số dư được cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'ID giao dịch (khoá chính)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng phát sinh giao dịch, FK -> users.id',
  `type` enum('checkin','daily_task','ad_reward','shop_purchase','admin_adjust') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại giao dịch, xác định nguồn gốc biến động số dư',
  `amount` bigint NOT NULL COMMENT 'Dương = cộng, âm = trừ',
  `balance_after` bigint UNSIGNED NOT NULL COMMENT 'Số dư sau khi áp dụng giao dịch này',
  `reference_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID bản ghi gốc (checkin_logs.id, shop_purchases.id...) để truy vết',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả thêm về giao dịch',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm phát sinh giao dịch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `wallet_transactions`
--
DELIMITER $$
CREATE TRIGGER `trg_wallet_tx_after_insert` AFTER INSERT ON `wallet_transactions` FOR EACH ROW BEGIN
    UPDATE wallets
       SET balance = NEW.balance_after
     WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ad_reward_logs`
--
ALTER TABLE `ad_reward_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ad_reward_logs_user_time` (`user_id`,`watched_at`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_badges_active` (`is_active`);

--
-- Indexes for table `checkin_logs`
--
ALTER TABLE `checkin_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_checkin_logs_user_date` (`user_id`,`checkin_date`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_user` (`user_id`),
  ADD KEY `idx_comments_post` (`post_id`),
  ADD KEY `idx_comments_parent` (`parent_comment_id`),
  ADD KEY `fk_comments_sticker` (`sticker_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_comment_likes_pair` (`comment_id`,`user_id`),
  ADD KEY `idx_comment_likes_user` (`user_id`);

--
-- Indexes for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_daily_tasks_active` (`is_active`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_follows_pair` (`follower_id`,`following_id`),
  ADD KEY `idx_follows_following` (`following_id`),
  ADD KEY `idx_follows_follower` (`follower_id`);

--
-- Indexes for table `frames`
--
ALTER TABLE `frames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_frames_active` (`is_active`);

--
-- Indexes for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_leaderboard_cache` (`user_id`,`period_type`,`period_key`),
  ADD KEY `idx_leaderboard_cache_period_rank` (`period_type`,`period_key`,`rank_position`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_actor` (`actor_id`),
  ADD KEY `idx_notif_recipient_read` (`recipient_id`,`is_read`,`created_at`),
  ADD KEY `idx_notifications_target` (`target_type`,`target_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_created_at` (`created_at`),
  ADD KEY `idx_posts_status_engagement` (`status`,`likes_count`,`comments_count`),
  ADD KEY `idx_posts_user` (`user_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_images_post_sort` (`post_id`,`sort_order`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_post_likes_pair` (`post_id`,`user_id`),
  ADD KEY `idx_post_likes_user` (`user_id`);

--
-- Indexes for table `post_saves`
--
ALTER TABLE `post_saves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_post_saves_pair` (`post_id`,`user_id`),
  ADD KEY `idx_post_saves_user` (`user_id`);

--
-- Indexes for table `shop_purchases`
--
ALTER TABLE `shop_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shop_purchases_user` (`user_id`),
  ADD KEY `idx_shop_purchases_item` (`item_type`,`item_id`);

--
-- Indexes for table `stickers`
--
ALTER TABLE `stickers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stickers_pack_sort` (`pack_id`,`sort_order`);

--
-- Indexes for table `sticker_packs`
--
ALTER TABLE `sticker_packs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sticker_packs_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_google_uid` (`google_uid`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `fk_users_active_frame` (`active_frame_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_badges_pair` (`user_id`,`badge_id`),
  ADD UNIQUE KEY `uq_user_badges_pinned_order` (`user_id`,`pinned_order`),
  ADD KEY `idx_user_badges_badge` (`badge_id`);

--
-- Indexes for table `user_daily_tasks`
--
ALTER TABLE `user_daily_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_daily_tasks` (`user_id`,`task_id`,`task_date`),
  ADD KEY `fk_user_daily_tasks_task` (`task_id`),
  ADD KEY `idx_user_daily_tasks_date` (`task_date`);

--
-- Indexes for table `user_frames`
--
ALTER TABLE `user_frames`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_frames_pair` (`user_id`,`frame_id`),
  ADD KEY `idx_user_frames_frame` (`frame_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_sticker_packs`
--
ALTER TABLE `user_sticker_packs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_sticker_packs_pair` (`user_id`,`pack_id`),
  ADD KEY `idx_user_sticker_packs_pack` (`pack_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wallet_tx_user_created` (`user_id`,`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad_reward_logs`
--
ALTER TABLE `ad_reward_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lượt xem quảng cáo (khoá chính)';

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID huy hiệu (khoá chính)';

--
-- AUTO_INCREMENT for table `checkin_logs`
--
ALTER TABLE `checkin_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lượt điểm danh (khoá chính)';

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bình luận (khoá chính)';

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lượt thích bình luận (khoá chính)';

--
-- AUTO_INCREMENT for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID nhiệm vụ (khoá chính)';

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID quan hệ follow (khoá chính)';

--
-- AUTO_INCREMENT for table `frames`
--
ALTER TABLE `frames`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID khung viền (khoá chính)';

--
-- AUTO_INCREMENT for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bản ghi xếp hạng (khoá chính)';

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID thông báo (khoá chính)';

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bài viết (khoá chính)';

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID ảnh (khoá chính)';

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lượt thích (khoá chính)';

--
-- AUTO_INCREMENT for table `post_saves`
--
ALTER TABLE `post_saves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID lượt lưu bài (khoá chính)';

--
-- AUTO_INCREMENT for table `shop_purchases`
--
ALTER TABLE `shop_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID giao dịch mua hàng (khoá chính)';

--
-- AUTO_INCREMENT for table `stickers`
--
ALTER TABLE `stickers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID sticker (khoá chính)';

--
-- AUTO_INCREMENT for table `sticker_packs`
--
ALTER TABLE `sticker_packs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID gói sticker (khoá chính)';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID người dùng (khoá chính)', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bản ghi sở hữu huy hiệu (khoá chính)';

--
-- AUTO_INCREMENT for table `user_daily_tasks`
--
ALTER TABLE `user_daily_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID tiến độ nhiệm vụ (khoá chính)';

--
-- AUTO_INCREMENT for table `user_frames`
--
ALTER TABLE `user_frames`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bản ghi sở hữu (khoá chính)';

--
-- AUTO_INCREMENT for table `user_sticker_packs`
--
ALTER TABLE `user_sticker_packs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID bản ghi sở hữu gói sticker (khoá chính)';

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID giao dịch (khoá chính)';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ad_reward_logs`
--
ALTER TABLE `ad_reward_logs`
  ADD CONSTRAINT `fk_ad_reward_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `checkin_logs`
--
ALTER TABLE `checkin_logs`
  ADD CONSTRAINT `fk_checkin_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_sticker` FOREIGN KEY (`sticker_id`) REFERENCES `stickers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `fk_comment_likes_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `fk_follows_follower` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_follows_following` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  ADD CONSTRAINT `fk_leaderboard_cache_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_actor` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifications_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `fk_post_images_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_post_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_saves`
--
ALTER TABLE `post_saves`
  ADD CONSTRAINT `fk_post_saves_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_saves_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_purchases`
--
ALTER TABLE `shop_purchases`
  ADD CONSTRAINT `fk_shop_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stickers`
--
ALTER TABLE `stickers`
  ADD CONSTRAINT `fk_stickers_pack` FOREIGN KEY (`pack_id`) REFERENCES `sticker_packs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_active_frame` FOREIGN KEY (`active_frame_id`) REFERENCES `frames` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `fk_user_badges_badge` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_badges_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_daily_tasks`
--
ALTER TABLE `user_daily_tasks`
  ADD CONSTRAINT `fk_user_daily_tasks_task` FOREIGN KEY (`task_id`) REFERENCES `daily_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_daily_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_frames`
--
ALTER TABLE `user_frames`
  ADD CONSTRAINT `fk_user_frames_frame` FOREIGN KEY (`frame_id`) REFERENCES `frames` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_frames_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `fk_user_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_sticker_packs`
--
ALTER TABLE `user_sticker_packs`
  ADD CONSTRAINT `fk_user_sticker_packs_pack` FOREIGN KEY (`pack_id`) REFERENCES `sticker_packs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_sticker_packs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `fk_wallets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `fk_wallet_tx_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
