<?php
include 'layout/header.php';
include 'includes/database.php';

// بررسی وجود course_id
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// دریافت اطلاعات دوره از دیتابیس
try {
    $stmt_course = $conn->prepare("SELECT * FROM courses WHERE id = ? AND status = 'active'");
    $stmt_course->execute([$course_id]);
    $course = $stmt_course->fetch(PDO::FETCH_ASSOC);
    
    // دیباگ: چک کن داده‌ها کشیده شده
    if (!$course) {
        echo '<div class="container"><p>دوره مورد نظر یافت نشد یا غیرفعال است! (course_id: ' . htmlspecialchars($course_id) . ')</p></div>';
        include 'layout/footer.php';
        exit;
    }
} catch (PDOException $e) {
    die('Error fetching course: ' . $e->getMessage());
}
?>

<div class="container">
    <section class="preview-section">
        <div class="section-header">
            <h2>پیش‌نمایش دوره: <?= htmlspecialchars($course['title']) ?></h2>
            <p>نگاهی به محتوای آموزشی این دوره</p>
        </div>
        
        <div class="preview-content">
            <div class="course-info">
                <img src="<?= resolveImagePath($course['image_url'] ?? null, 'assets/images/default-course.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="course-image">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p>سطح: <?= htmlspecialchars($course['level']) ?></p>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <div class="course-price"><?= htmlspecialchars($course['price']) ? 'رایگان' : number_format($course['price'], 0) . ' تومان' ?></div>
                <div class="course-preview">
                    <p><?= htmlspecialchars($course['preview_content'] ?? 'پیش‌نمایش برای این دوره در دسترس نیست.') ?></p>
                </div>
                <div class="course-actions">
                    <a href="enroll.php?course_id=<?= $course['id'] ?>" class="btn-enroll">ثبت‌نام در دوره</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>