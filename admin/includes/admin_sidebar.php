<?php
// اطمینان از اتصال به دیتابیس
include '../includes/database.php';

// بررسی وجود جدول‌های مختلف در دیتابیس
$tables = [
    'posts' => $conn->query("SHOW TABLES LIKE 'posts'")->rowCount() > 0,
    'instructors' => $conn->query("SHOW TABLES LIKE 'instructors'")->rowCount() > 0,
    'users' => $conn->query("SHOW TABLES LIKE 'users'")->rowCount() > 0,
    'comments' => $conn->query("SHOW TABLES LIKE 'comments'")->rowCount() > 0,
    'ratings' => $conn->query("SHOW TABLES LIKE 'ratings'")->rowCount() > 0,
    'contact_messages' => $conn->query("SHOW TABLES LIKE 'contact_messages'")->rowCount() > 0,
    'enrollments' => $conn->query("SHOW TABLES LIKE 'enrollments'")->rowCount() > 0
];
?>

<!-- Sidebar Section -->
<div class="admin-sidebar">
    <h3 class="sidebar-title">داشبورد مدیریت</h3>
    <ul class="sidebar-nav">
        <!-- دکمه داشبورد -->
        <li>
            <a href="index.php" class="sidebar-link active">
                <i class="bi bi-house-fill"></i>
                <span>داشبورد</span>
            </a>
        </li>

                <!-- بخش پست‌ها -->
                <?php if($tables['posts']): ?>
                <li>
                    <a href="admin_posts.php" class="sidebar-link">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>پست‌ها</span>
                    </a>
                    <ul>
                        <li><a href="create_post.php">ایجاد پست جدید</a></li>
                        <li><a href="edit_post.php">ویرایش پست‌ها</a></li>
                        <li><a href="delete_post.php">حذف پست‌ها</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- بخش اساتید -->
                <?php if($tables['instructors']): ?>
                <li>
                    <a href="admin_teachers.php" class="sidebar-link">
                        <i class="bi bi-person-video3"></i>
                        <span>اساتید</span>
                    </a>
                    <ul>
                        <li><a href="create_teacher.php">افزودن استاد جدید</a></li>
                        <li><a href="edit_teacher.php">ویرایش اساتید</a></li>
                        <li><a href="delete_teacher.php">حذف اساتید</a></li>
                        <li><a href="admin_instructors_report.php">گزارش اساتید</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- بخش کاربران -->
                <?php if($tables['users']): ?>
                <li>
                    <a href="admin_users.php" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>کاربران</span>
                    </a>
                    <ul>
                        <li><a href="create_user.php">افزودن کاربر</a></li>
                        <li><a href="edit_user.php">ویرایش کاربران</a></li>
                        <li><a href="delete_user.php">حذف کاربران</a></li>
                        <?php if($tables['enrollments']): ?>
                        <li><a href="admin_enrollments_report.php">گزارش ثبت‌نام‌ها</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- بخش کامنت‌ها -->
                <?php if($tables['comments']): ?>
                <li>
                    <a href="admin_comments.php" class="sidebar-link">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>کامنت‌ها</span>
                    </a>
                    <ul>
                        <li><a href="admin_comments.php">مدیریت کامنت‌ها</a></li>
                        <li><a href="admin_comments.php?status=approved">کامنت‌های تایید شده</a></li>
                        <li><a href="admin_comments.php?status=pending">کامنت‌های در انتظار</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- بخش امتیازها -->
                <?php if($tables['ratings']): ?>
                <li>
                    <a href="admin_ratings.php" class="sidebar-link">
                        <i class="bi bi-star-fill"></i>
                        <span>امتیازها</span>
                    </a>
                    <ul>
                        <li><a href="admin_ratings.php">مدیریت امتیازها</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- بخش پیام‌های تماس -->
                <?php if($tables['contact_messages']): ?>
                <li>
                    <a href="contact_messages.php" class="sidebar-link">
                        <i class="bi bi-envelope-fill"></i>
                        <span>پیام‌های تماس</span>
                    </a>
                    <ul>
                        <li><a href="contact_messages.php">مشاهده پیام‌ها</a></li>
                        <li><a href="contact_messages.php?action=manage">مدیریت پیام‌ها</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                

                <!-- دکمه خروج -->
                <li>
                    <a href="logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>خروج</span>
                    </a>
                </li>
            </ul>
        </div>