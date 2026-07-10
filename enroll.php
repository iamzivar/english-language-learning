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
} catch (PDOException $e) {
    die('Error fetching course: ' . $e->getMessage());
}

if (!$course) {
    echo '<div class="container"><p>دوره مورد نظر یافت نشد یا غیرفعال است!</p></div>';
    include 'layout/footer.php';
    exit;
}

// بررسی وضعیت لاگین کاربر
$current_user = $_SESSION['user'] ?? null;
if (!$current_user) {
    header('Location: login.php?redirect=enroll.php?course_id=' . $course_id);
    exit;
}
?>

<div class="container app-container">
    <section class="enroll-section">
        <div class="section-header">
            <h2>ثبت‌نام در <?= htmlspecialchars($course['title']) ?></h2>
            <p>برای شروع یادگیری، اطلاعات خود را وارد کنید</p>
        </div>
        
        <div class="enroll-content surface" style="padding:1rem; border-radius:16px;">
            <div class="course-info">
                <img src="<?= htmlspecialchars($course['image_url'] ?? 'assets/images/default-course.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="course-image">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p>سطح: <?= htmlspecialchars($course['level']) ?></p>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <div class="course-price"><?= htmlspecialchars($course['price']) ?></div>
            </div>
            
            <form class="enroll-form" action="process_enrollment.php" method="POST">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $current_user['id'] ?>">
                
                <div class="form-group">
                    <label for="full_name">نام کامل</label>
                    <input type="text" id="full_name" name="full_name" class="input" value="<?= htmlspecialchars($current_user['username']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input type="email" id="email" name="email" class="input" value="<?= htmlspecialchars($current_user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="payment_method">روش پرداخت</label>
                    <select id="payment_method" name="payment_method" class="select" required>
                        <option value="online">پرداخت آنلاین</option>
                        <option value="card">کارت بانکی</option>
                        <option value="free" <?= $course['price'] === 'رایگان' ? 'selected' : '' ?>>رایگان</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-enroll">تأیید و ثبت‌نام</button>
            </form>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>