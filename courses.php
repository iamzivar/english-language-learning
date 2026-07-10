<?php
// فعال کردن نمایش خطاها برای دیباگ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

// دریافت اطلاعات دوره‌ها از دیتابیس (فقط دوره‌های فعال)
try {
    $stmt_courses = $conn->prepare("SELECT * FROM courses WHERE status = 'active' ORDER BY id ASC");
    $stmt_courses->execute();
    $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching courses: ' . $e->getMessage());
}

// دریافت اطلاعات اساتید برای نمایش در بخش دوره‌ها
try {
    $stmt_instructors = $conn->prepare("
        SELECT i.id, i.name, i.bio, i.photo_url,
               COALESCE(AVG(r.rating), 0) as avg_rating,
               COUNT(r.id) as rating_count
        FROM instructors i
        LEFT JOIN ratings r ON i.id = r.instructor_id
        GROUP BY i.id
        ORDER BY avg_rating DESC
        LIMIT 6
    ");
    $stmt_instructors->execute();
    $instructors = $stmt_instructors->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching instructors: ' . $e->getMessage());
}
?>

<!-- Hero Section for Courses -->
<section class="courses-hero hero">
    <div class="courses-hero-bg"></div>
    <div class="container app-container surface">
        <div class="courses-hero-content">
            <h1>دوره‌های آموزشی زبان انگلیسی</h1>
            <p>مجموعه کامل دوره‌های تخصصی برای تمام سطوح و نیازهای شما</p>
            <div class="courses-hero-stats stack-md">
                <div class="stat-item">
                    <span class="stat-number">۱۵+</span>
                    <span class="stat-label">دوره تخصصی</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۵۰۰+</span>
                    <span class="stat-label">دانشجو راضی</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۹۸%</span>
                    <span class="stat-label">رضایت دانشجویان</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container app-container">
    <!-- Course Categories Filter -->
    <section class="course-filters">
        <div class="filter-buttons">
            <button class="filter-btn active btn btn-soft" data-filter="all">همه دوره‌ها</button>
            <button class="filter-btn btn btn-soft" data-filter="beginner">مبتدی</button>
            <button class="filter-btn btn btn-soft" data-filter="intermediate">متوسط</button>
            <button class="filter-btn btn btn-soft" data-filter="advanced">پیشرفته</button>
            <button class="filter-btn btn btn-soft" data-filter="exam">آزمون‌ها</button>
            <button class="filter-btn btn btn-soft" data-filter="business">تجاری</button>
            <button class="filter-btn btn btn-soft" data-filter="conversation">مکالمه</button>
        </div>
    </section>

    <!-- Main Courses Section -->
    <section class="courses-section">
        <div class="section-header">
            <h2>دوره‌های آموزشی ما</h2>
            <p>انتخاب کنید و یادگیری را شروع کنید</p>
        </div>
        
        <div class="courses-grid grid">
            <?php if (empty($courses)): ?>
                <p>هیچ دوره‌ای یافت نشد.</p>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card card col-4" data-category="<?= htmlspecialchars($course['category']) ?>">
                        <div class="course-content">
                            <div class="course-image">
                                <?php $courseImg = resolveCourseImage($course['title'] ?? null, $course['category'] ?? null, $course['image_url'] ?? null, 'assets/images/grammar1.png'); ?>
                                <img src="<?= htmlspecialchars($courseImg) ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                            </div>
                            <div class="course-header">
                                <h3><?= htmlspecialchars($course['title']) ?></h3>
                                <div class="course-rating">
                                    <?php
                                    $rating = floatval($course['rating']);
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < $fullStars) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i == $fullStars && $halfStar) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span>(<?= number_format($course['rating'], 1) ?>)</span>
                                </div>
                            </div>
                            <div class="course-level-badge <?= htmlspecialchars($course['category']) ?>">
                                <?= htmlspecialchars($course['level']) ?>
                            </div>
                            <p><?= htmlspecialchars($course['description']) ?></p>
                            <div class="course-features">
                                <div class="feature">
                                    <i class="fas fa-clock"></i>
                                    <span><?= htmlspecialchars($course['duration']) ?></span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-users"></i>
                                    <span><?= htmlspecialchars($course['students']) ?></span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-play-circle"></i>
                                    <span><?= htmlspecialchars($course['lessons']) ?></span>
                                </div>
                            </div>
                            <div class="course-price"><?= htmlspecialchars($course['price']) ?></div>
                            <div class="course-actions">
                                <a href="enroll.php?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-enroll">ثبت‌نام</a>
                                <a href="preview.php?course_id=<?= $course['id'] ?>" class="btn btn-soft btn-preview">پیش‌نمایش</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Course Features Section -->
    <section class="course-features-section section">
        <div class="section-header">
            <h2>چرا دوره‌های ما؟</h2>
            <p>ویژگی‌های منحصر به فرد که ما را متمایز می‌کند</p>
        </div>
        
        <div class="features-grid grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>اساتید مجرب</h3>
                <p>اساتید با تجربه و دارای مدارک بین‌المللی</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>انعطاف زمانی</h3>
                <p>دسترسی ۲۴/۷ به محتوای آموزشی</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>گواهی پایان دوره</h3>
                <p>دریافت گواهی معتبر پس از اتمام دوره</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>پشتیبانی ۲۴/۷</h3>
                <p>پشتیبانی کامل در تمام ساعات شبانه‌روز</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>دسترسی موبایل</h3>
                <p>یادگیری در هر زمان و مکان</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>گروه‌های کوچک</h3>
                <p>کلاس‌های با تعداد محدود برای یادگیری بهتر</p>
            </div>
        </div>
    </section>

    <!-- Featured Instructors Section -->
    <section class="featured-instructors section">
        <div class="section-header">
            <h2>اساتید مجرب ما</h2>
            <p>با بهترین اساتید زبان انگلیسی آشنا شوید</p>
        </div>
        
        <div class="teachers-grid grid">
            <?php foreach ($instructors as $instructor): ?>
                <?php $instructorImg = resolveImagePath($instructor['photo_url'] ?? null, 'assets/images/default-teacher.jpg'); ?>
                <div class="teacher-card card col-4">
                    <div class="teacher-image">
                        <img src="<?= htmlspecialchars($instructorImg) ?>" alt="<?= htmlspecialchars($instructor['name']) ?>">
                        <div class="teacher-overlay">
                            <a href="teacher-profile.php?id=<?= $instructor['id'] ?>" class="view-profile-btn btn btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="teacher-content">
                        <h4><?= htmlspecialchars($instructor['name']) ?></h4>
                        <p><?= htmlspecialchars(substr($instructor['bio'], 0, 80)) ?>...</p>
                        <div class="teacher-rating">
                            <?php
                            $fullStars = floor($instructor['avg_rating']);
                            $halfStar = ($instructor['avg_rating'] - $fullStars) >= 0.5;
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $fullStars) {
                                    echo '<i class="fas fa-star star"></i>';
                                } elseif ($i == $fullStars && $halfStar) {
                                    echo '<i class="fas fa-star-half-alt star"></i>';
                                } else {
                                    echo '<i class="far fa-star star"></i>';
                                }
                            }
                            ?>
                            <span class="rating-text"><?= number_format($instructor['avg_rating'], 1) ?> (<?= $instructor['rating_count'] ?> رأی)</span>
                        </div>
                        <a href="teacher-profile.php?id=<?= $instructor['id'] ?>" class="view-profile btn btn-soft">مشاهده پروفایل</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
document.querySelectorAll('.btn-preview, .btn-enroll').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault(); // جلوگیری از رفتار پیش‌فرض فقط برای اطمینان
        window.location.href = button.getAttribute('href'); // هدایت به URL
    });
});
</script>

<?php include 'layout/footer.php'; ?>
```