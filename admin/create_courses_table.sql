-- Create courses table
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `level` enum('beginner','intermediate','advanced','exam','business','conversation','grammar','writing') NOT NULL,
  `duration` varchar(50) NOT NULL,
  `lessons_count` int(11) NOT NULL,
  `students_count` int(11) DEFAULT 0,
  `price` decimal(10,2) DEFAULT 0.00,
  `rating` decimal(3,2) DEFAULT 0.00,
  `rating_count` int(11) DEFAULT 0,
  `instructor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `instructor_id` (`instructor_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample courses data
INSERT INTO `courses` (`title`, `description`, `level`, `duration`, `lessons_count`, `students_count`, `price`, `rating`, `rating_count`, `instructor_id`) VALUES
('دوره مبتدی زبان انگلیسی', 'آموزش اصول اولیه زبان انگلیسی برای افراد مبتدی. شامل گرامر پایه، واژگان ضروری و مکالمه ساده.', 'beginner', '۸ هفته', 48, 150, 0.00, 4.80, 45, 1),
('دوره متوسط زبان انگلیسی', 'تقویت مهارت‌های زبانی در سطح متوسط. شامل گرامر پیشرفته، واژگان تخصصی و مکالمه روان.', 'intermediate', '۱۲ هفته', 72, 200, 500000.00, 4.60, 38, 2),
('دوره پیشرفته زبان انگلیسی', 'آموزش سطح پیشرفته برای تسلط کامل بر زبان انگلیسی. شامل گرامر پیچیده و مکالمه حرفه‌ای.', 'advanced', '۱۶ هفته', 96, 120, 800000.00, 4.90, 52, 3),
('آمادگی آزمون IELTS', 'دوره تخصصی آمادگی برای آزمون IELTS. شامل تمرین‌های هر چهار مهارت و استراتژی‌های موفقیت.', 'exam', '۱۰ هفته', 60, 80, 1200000.00, 4.90, 41, 4),
('آمادگی آزمون TOEFL', 'دوره آمادگی برای آزمون TOEFL. شامل تمرین‌های پیشرفته و نمونه سوالات واقعی.', 'exam', '۱۲ هفته', 72, 60, 1500000.00, 4.70, 33, 5),
('انگلیسی تجاری', 'آموزش زبان انگلیسی برای محیط‌های کاری. شامل واژگان تجاری، مکالمه رسمی و نگارش حرفه‌ای.', 'business', '۸ هفته', 48, 90, 600000.00, 4.80, 28, 6),
('مکالمه انگلیسی', 'تمرکز بر مهارت مکالمه و تلفظ صحیح. شامل تمرین‌های عملی و مکالمات روزمره.', 'conversation', '۶ هفته', 36, 180, 400000.00, 4.90, 67, 7),
('گرامر پیشرفته انگلیسی', 'آموزش جامع گرامر انگلیسی در سطح پیشرفته. شامل ساختارهای پیچیده و کاربردهای عملی.', 'grammar', '۱۰ هفته', 60, 110, 700000.00, 4.60, 42, 8),
('نگارش آکادمیک انگلیسی', 'آموزش نگارش مقالات علمی و آکادمیک. شامل ساختار استاندارد و سبک‌های مختلف نگارش.', 'writing', '۸ هفته', 48, 70, 500000.00, 4.80, 31, 9);
