-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 10:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `english_language_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `status`, `created_at`) VALUES
(1, 1, 3, 'مطالب بسیار مفید بود، ممنون!', 1, '2025-04-23 06:30:00'),
(2, 2, 4, 'لطفاً مثال‌های بیشتری قرار دهید.', 0, '2025-04-24 07:30:00'),
(3, 3, 5, 'این نکات برای آزمونم خیلی کمک کرد.', 1, '2025-04-25 08:30:00'),
(4, 4, 6, 'عالی بود، منتظر مطالب بعدی هستم.', 1, '2025-04-26 09:30:00'),
(5, 5, 7, 'تلفظ‌ها خیلی واضح توضیح داده شده.', 1, '2025-04-27 10:30:00'),
(6, 6, 8, 'کودکانم از این روش‌ها لذت بردند.', 1, '2025-04-28 11:30:00'),
(7, 7, 9, 'تمرین‌های شنیداری عالی بودند.', 0, '2025-04-29 12:30:00'),
(8, 8, 10, 'ایمیل‌نویسی را بهتر یاد گرفتم.', 1, '2025-04-30 13:30:00'),
(9, 9, 3, 'اصطلاحات جدید و جالبی یاد گرفتم.', 1, '2025-05-01 14:30:00'),
(10, 10, 4, 'گرامر پیچیده را ساده کردید، ممنون!', 1, '2025-05-02 15:30:00'),
(11, 11, 5, 'مفید برای جلسات کاری من بود.', 1, '2025-05-03 16:30:00'),
(12, 12, 6, 'لطفاً نکات بیشتری برای TOEFL بگذارید.', 0, '2025-05-04 17:30:00'),
(13, 13, 13, 'نکات نگارش خیلی کاربردی بود.', 1, '2025-05-05 15:00:00'),
(14, 14, 14, 'لطفاً عبارات بیشتری برای سفر بگذارید.', 0, '2025-05-06 16:00:00'),
(15, 15, 15, 'گرامر را خیلی ساده توضیح دادید.', 1, '2025-05-07 17:00:00'),
(16, 16, 16, 'ممنون بابت نکات TOEIC.', 1, '2025-05-08 18:00:00'),
(17, 17, 17, 'بازی‌ها برای بچه‌ها عالی بودند.', 1, '2025-05-09 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'علی محمدی', 'ali@example.com', 'لطفاً دوره‌های آنلاین بیشتری برگزار کنید.', '2025-04-23 06:30:00'),
(2, 'زهرا احمدی', 'zahra@example.com', 'چگونه می‌توانم در کلاس‌های IELTS ثبت‌نام کنم؟', '2025-04-24 07:30:00'),
(3, 'رضا حسینی', 'reza@example.com', 'سایت بسیار خوبی دارید، ممنون!', '2025-04-25 08:30:00'),
(4, 'مریم رضایی', 'maryam@example.com', 'لطفاً منابع رایگان بیشتری قرار دهید.', '2025-04-26 09:30:00'),
(5, 'حسین کاظمی', 'hossein@example.com', 'مشکل در دانلود فایل‌های آموزشی دارم.', '2025-04-27 10:30:00'),
(6, 'فاطمه رحیمی', 'fatemeh@example.com', 'لطفاً دوره‌های پیشرفته اضافه کنید.', '2025-04-28 11:30:00'),
(7, 'محمد شریفی', 'mohammad@example.com', 'کیفیت ویدیوها عالی است، تشکر.', '2025-04-29 12:30:00'),
(8, 'نازنین مرادی', 'nazanin@example.com', 'لطفاً راهنمای آزمون TOEFL بگذارید.', '2025-04-30 13:30:00'),
(9, 'امیر علوی', 'amir@example.com', 'چگونه می‌توانم استاد شوم؟', '2025-05-01 14:30:00'),
(10, 'سارا حسنی', 'sara@example.com', 'لطفاً کلاس‌های مکالمه آنلاین برگزار کنید.', '2025-05-02 15:30:00'),
(11, 'مهدی احمدی', 'mehdi@example.com', 'سایت شما بسیار کاربرپسند است.', '2025-05-03 16:30:00'),
(12, 'الناز رحمانی', 'elnaz@example.com', 'لطفاً مطالب بیشتری برای گرامر بگذارید.', '2025-05-04 17:30:00'),
(13, 'سینا محمدی', 'sina@example.com', 'لطفاً دوره‌های نگارش اضافه کنید.', '2025-05-05 15:00:00'),
(14, 'لیلا هاشمی', 'leila@example.com', 'چگونه می‌توانم در کلاس‌های آنلاین شرکت کنم؟', '2025-05-06 16:00:00'),
(15, 'کاوه احمدی', 'kaveh@example.com', 'سایت عالی است، ممنون!', '2025-05-07 17:00:00'),
(16, 'پریسا رضایی', 'parisa@example.com', 'لطفاً منابع بیشتری برای TOEIC بگذارید.', '2025-05-08 18:00:00'),
(17, 'بابک کریمی', 'babak@example.com', 'لطفاً آموزش‌های ویدیویی بیشتری اضافه کنید.', '2025-05-09 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `bio`, `created_at`) VALUES
(1, 'دکتر احمدی', 'استاد زبان انگلیسی با 15 سال تجربه تدریس', '2025-04-01 04:30:00'),
(2, 'خانم محمدی', 'مدرس IELTS با مدرک بین‌المللی', '2025-04-02 05:30:00'),
(3, 'آقای رضایی', 'متخصص آموزش مکالمه انگلیسی', '2025-04-03 06:30:00'),
(4, 'خانم حسینی', 'مدرس گرامر و نگارش انگلیسی', '2025-04-04 07:30:00'),
(5, 'دکتر علوی', 'متخصص آموزش زبان تجاری', '2025-04-05 08:30:00'),
(6, 'آقای کاظمی', 'مدرس زبان انگلیسی کودکان', '2025-04-06 09:30:00'),
(7, 'خانم رحیمی', 'مدرس آمادگی آزمون TOEFL', '2025-04-07 10:30:00'),
(8, 'دکتر شریفی', 'استاد آموزش تلفظ انگلیسی', '2025-04-08 11:30:00'),
(9, 'آقای حسنی', 'مدرس واژگان پیشرفته', '2025-04-09 12:30:00'),
(10, 'خانم مرادی', 'متخصص آموزش مهارت‌های شنیداری', '2025-04-10 13:30:00'),
(11, 'خانم یوسفی', 'مدرس آمادگی آزمون IELTS', '2025-04-11 11:00:00'),
(12, 'آقای محمودی', 'متخصص آموزش واژگان انگلیسی', '2025-04-12 12:00:00'),
(13, 'دکتر حسنی', 'استاد آموزش گرامر پیشرفته', '2025-04-13 13:00:00'),
(14, 'خانم شریفی', 'مدرس مکالمه تجاری', '2025-04-14 14:00:00'),
(15, 'آقای رحمانی', 'متخصص آموزش زبان به کودکان', '2025-04-15 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image_url`, `instructor_id`, `created_at`) VALUES
(1, 'نکات کلیدی گرامر انگلیسی', 'بررسی زمان‌های حال ساده و استمراری...', 'images/grammar1.jpg', 1, '2025-04-11 06:30:00'),
(2, 'مکالمه روزمره انگلیسی', 'عبارات پرکاربرد در مکالمات روزمره...', 'images/conversation1.jpg', 2, '2025-04-12 07:30:00'),
(3, 'آمادگی برای آزمون IELTS', 'استراتژی‌های موفقیت در بخش رایتینگ...', 'images/ielts1.jpg', 3, '2025-04-13 08:30:00'),
(4, 'واژگان پیشرفته انگلیسی', 'یادگیری واژگان سطح C1 و C2...', 'images/vocabulary1.jpg', 4, '2025-04-14 09:30:00'),
(5, 'تلفظ صحیح در انگلیسی', 'راهنمای بهبود تلفظ کلمات...', 'images/pronunciation1.jpg', 5, '2025-04-15 10:30:00'),
(6, 'زبان انگلیسی برای کودکان', 'روش‌های جذاب آموزش به کودکان...', 'images/kids1.jpg', 6, '2025-04-16 11:30:00'),
(7, 'مهارت‌های شنیداری', 'تمرین‌های تقویت مهارت شنیداری...', 'images/listening1.jpg', 7, '2025-04-17 12:30:00'),
(8, 'نوشتن ایمیل رسمی', 'نکات نگارش ایمیل‌های حرفه‌ای...', 'images/email1.jpg', 8, '2025-04-18 13:30:00'),
(9, 'اصطلاحات عامیانه انگلیسی', 'یادگیری اصطلاحات روزمره...', 'images/slang1.jpg', 9, '2025-04-19 14:30:00'),
(10, 'گرامر پیشرفته', 'بررسی ساختارهای پیچیده گرامری...', 'images/grammar2.jpg', 1, '2025-04-20 15:30:00'),
(11, 'مکالمه تجاری', 'عبارات کلیدی در جلسات کاری...', 'images/business1.jpg', 5, '2025-04-21 16:30:00'),
(12, 'آمادگی آزمون TOEFL', 'نکات کلیدی برای موفقیت در TOEFL...', 'images/toefl1.jpg', 7, '2025-04-22 17:30:00'),
(13, 'نکات نگارش آکادمیک', 'راهنمای نگارش مقالات علمی...', 'images/academic1.jpg', 11, '2025-04-23 15:00:00'),
(14, 'مکالمه در سفر', 'عبارات ضروری برای سفرهای خارجی...', 'images/travel1.jpg', 12, '2025-04-24 16:00:00'),
(15, 'گرامر برای مبتدیان', 'آشنایی با گرامر پایه انگلیسی...', 'images/grammar3.jpg', 13, '2025-04-25 17:00:00'),
(16, 'تکنیک‌های آزمون TOEIC', 'استراتژی‌های موفقیت در TOEIC...', 'images/toeic1.jpg', 14, '2025-04-26 18:00:00'),
(17, 'آموزش زبان با بازی', 'روش‌های بازی‌محور برای یادگیری...', 'images/game1.jpg', 15, '2025-04-27 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `instructor_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 5, 'تدریس بسیار روان و کاربردی', '2025-04-23 06:30:00'),
(2, 2, 4, 4, 'مثال‌ها می‌توانست بیشتر باشد', '2025-04-24 07:30:00'),
(3, 3, 5, 5, 'استاد بسیار حرفه‌ای', '2025-04-25 08:30:00'),
(4, 4, 6, 3, 'محتوا خوب بود ولی نیاز به توضیحات بیشتر', '2025-04-26 09:30:00'),
(5, 5, 7, 4, 'تلفظ‌ها را خوب توضیح دادید', '2025-04-27 10:30:00'),
(6, 6, 8, 5, 'روش تدریس برای کودکان عالی بود', '2025-04-28 11:30:00'),
(7, 7, 9, 4, 'تمرین‌های شنیداری کاربردی بودند', '2025-04-29 12:30:00'),
(8, 8, 10, 5, 'ایمیل‌نویسی را به خوبی یاد گرفتم', '2025-04-30 13:30:00'),
(9, 9, 3, 4, 'اصطلاحات جدید و جالبی یاد گرفتم', '2025-05-01 14:30:00'),
(10, 10, 4, 3, 'توضیحات کمی پیچیده بود', '2025-05-02 15:30:00'),
(11, 1, 5, 5, 'گرامر را خیلی خوب توضیح می‌دهند', '2025-05-03 16:30:00'),
(12, 2, 6, 4, 'مکالمه را جذاب تدریس می‌کنید', '2025-05-04 17:30:00'),
(13, 11, 13, 4, 'تدریس نگارش خیلی خوب بود.', '2025-05-05 15:00:00'),
(14, 12, 14, 5, 'واژگان را عالی توضیح دادید.', '2025-05-06 16:00:00'),
(15, 13, 15, 3, 'نیاز به مثال‌های بیشتر دارم.', '2025-05-07 17:00:00'),
(16, 14, 16, 5, 'نکات TOEIC بسیار مفید بود.', '2025-05-08 18:00:00'),
(17, 15, 17, 4, 'روش‌های تدریس برای کودکان جذاب بود.', '2025-05-09 19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin1', 'admin1@example.com', '$2y$10$hashedpassword1', 'admin', '2025-04-01 04:30:00'),
(2, 'admin2', 'admin2@example.com', '$2y$10$hashedpassword2', 'admin', '2025-04-02 05:30:00'),
(3, 'ali_rezaei', 'ali.rezaei@example.com', '$2y$10$hashedpassword3', 'user', '2025-04-03 06:30:00'),
(4, 'zahra_ahmadi', 'zahra.ahmadi@example.com', '$2y$10$hashedpassword4', 'user', '2025-04-04 07:30:00'),
(5, 'mohammad_hosseini', 'mohammad.hosseini@example.com', '$2y$10$hashedpassword5', 'user', '2025-04-05 08:30:00'),
(6, 'fatemeh_rahimi', 'fatemeh.rahimi@example.com', '$2y$10$hashedpassword6', 'user', '2025-04-06 09:30:00'),
(7, 'mehdi_kazemi', 'mehdi.kazemi@example.com', '$2y$10$hashedpassword7', 'user', '2025-04-07 10:30:00'),
(8, 'nazanin_moradi', 'nazanin.moradi@example.com', '$2y$10$hashedpassword8', 'user', '2025-04-08 11:30:00'),
(9, 'amir_sharifi', 'amir.sharifi@example.com', '$2y$10$hashedpassword9', 'user', '2025-04-09 12:30:00'),
(10, 'sara_hasani', 'sara.hasani@example.com', '$2y$10$hashedpassword10', 'user', '2025-04-10 13:30:00'),
(11, 'hossein_alavi', 'hossein.alavi@example.com', '$2y$10$hashedpassword11', 'user', '2025-04-11 14:30:00'),
(12, 'elnaz_rahmani', 'elnaz.rahmani@example.com', '$2y$10$hashedpassword12', 'user', '2025-04-12 15:30:00'),
(13, 'sina_mohammadi', 'sina.mohammadi@example.com', '$2y$10$hashedpassword13', 'user', '2025-05-05 13:00:00'),
(14, 'leila_hashemi', 'leila.hashemi@example.com', '$2y$10$hashedpassword14', 'user', '2025-05-06 14:00:00'),
(15, 'kaveh_ahmadi', 'kaveh.ahmadi@example.com', '$2y$10$hashedpassword15', 'user', '2025-05-07 15:00:00'),
(16, 'parisa_rezaei', 'parisa.rezaei@example.com', '$2y$10$hashedpassword16', 'user', '2025-05-08 16:00:00'),
(17, 'babak_karimi', 'babak.karimi@example.com', '$2y$10$hashedpassword17', 'user', '2025-05-09 17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
