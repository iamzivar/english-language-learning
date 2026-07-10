<?php
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

// دریافت 3 استاد برتر با میانگین امتیاز
$stmt_teachers = $conn->prepare("
    SELECT i.id, i.name, i.bio, i.photo_url, 
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as rating_count
    FROM instructors i
    LEFT JOIN ratings r ON i.id = r.instructor_id
    GROUP BY i.id
    ORDER BY avg_rating DESC, rating_count DESC
    LIMIT 3
");
$stmt_teachers->execute();
$teachers = $stmt_teachers->fetchAll();

// دریافت 3 پست اخیر با اطلاعات نویسنده
$stmt_posts = $conn->prepare("
    SELECT p.id, p.title, p.content, p.created_at, p.image_url,
           i.name as instructor_name, i.photo_url as instructor_photo
    FROM posts p
    JOIN instructors i ON p.instructor_id = i.id
    ORDER BY p.created_at DESC
    LIMIT 3
");
$stmt_posts->execute();
$posts = $stmt_posts->fetchAll();

// دریافت کامنت‌های هر پست
foreach ($posts as &$post) {
    $stmt_comments = $conn->prepare("
        SELECT c.content, u.username
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at DESC
        LIMIT 2
    ");
    $stmt_comments->execute([$post['id']]);
    $post['comments'] = $stmt_comments->fetchAll();
}
unset($post);
?>

<!-- محتوای اصلی صفحه -->
<div class="app-container">
    <section class="hero">
        <div class="app-container">
            <div class="hero-content">
            <h1>یادگیری زبان انگلیسی را با بهترین اساتید آغاز کنید</h1>
            <p>با روش‌های نوین آموزشی و اساتید مجرب، انگلیسی را مانند زبان مادری بیاموزید</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">ثبت‌نام کنید</a>
                <a href="teachers.php" class="btn btn-soft">مشاهده اساتید</a>
                <a href="courses.php" class="btn btn-soft">دوره‌های آموزشی</a>
            </div>
            </div>
        </div>
    </section>

    <!-- بخش اساتید برتر -->
    <section class="featured-teachers section">
        <div class="section-header">
            <h2>اساتید برتر ما</h2>
            <p>با بهترین اساتید زبان انگلیسی آشنا شوید</p>
        </div>
        
        <div class="teachers-grid grid">
            <?php foreach ($teachers as $teacher): ?>
                <?php $teacherImg = resolveImagePath($teacher['photo_url'] ?? null, 'assets/images/default-teacher.jpg'); ?>
                <div class="teacher-card card col-4">
                    <img src="<?= htmlspecialchars($teacherImg) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>">
                    <h3><?= htmlspecialchars($teacher['name']) ?></h3>
                    <p><?= htmlspecialchars(substr($teacher['bio'], 0, 100)) ?>...</p>
                    <div class="teacher-rating">
                        <?php
                        $fullStars = floor($teacher['avg_rating']);
                        $halfStar = ($teacher['avg_rating'] - $fullStars) >= 0.5;
                        
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
                        <span class="rating-text"><?= number_format($teacher['avg_rating'], 1) ?> (<?= $teacher['rating_count'] ?> رأی)</span>
                    </div>
                    <a href="teacher-profile.php?id=<?= $teacher['id'] ?>" class="view-profile btn btn-soft">مشاهده پروفایل</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- بخش آخرین مقالات -->
    <section class="latest-posts section">
        <div class="section-header">
            <h2>آخرین مقالات آموزشی</h2>
            <p>جدیدترین مطالب آموزشی از اساتید ما</p>
        </div>
        
        <div class="posts-grid grid">
            <?php foreach ($posts as $post): ?>
                <?php $postImg = resolveImagePath($post['image_url'] ?? null, 'assets/images/default-post.jpg'); ?>
                <article class="post-card card col-4">
                    <img src="<?= htmlspecialchars($postImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
                    <div class="post-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars(substr(strip_tags($post['content']), 0, 150)) ?>...</p>
                        <div class="post-meta">
                            <span class="author"><?= htmlspecialchars($post['instructor_name']) ?></span>
                            <span class="date"><?= htmlspecialchars($post['created_at']) ?></span>
                        </div>
                        <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-soft">ادامه مطلب</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>