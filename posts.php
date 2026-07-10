<?php
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

// دریافت تمام پست‌ها با اطلاعات نویسنده
$stmt_posts = $conn->prepare("
    SELECT p.id, p.title, p.content, p.created_at, p.image_url,
           i.name as instructor_name, i.photo_url as instructor_photo
    FROM posts p
    JOIN instructors i ON p.instructor_id = i.id
    ORDER BY p.created_at DESC
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
        LIMIT 3
    ");
    $stmt_comments->execute([$post['id']]);
    $post['comments'] = $stmt_comments->fetchAll();
}
unset($post);
?>

<!-- Hero Section for Posts -->
<section class="posts-hero hero">
    <div class="posts-hero-bg"></div>
    <div class="container app-container surface">
        <div class="posts-hero-content">
            <h1>مقالات آموزشی زبان انگلیسی</h1>
            <p>جدیدترین مطالب آموزشی، نکات و ترفندهای یادگیری از اساتید مجرب ما</p>
            <div class="posts-hero-stats stack-md">
                <div class="stat-item">
                    <span class="stat-number">۵۰+</span>
                    <span class="stat-label">مقاله آموزشی</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۱۵</span>
                    <span class="stat-label">استاد نویسنده</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۱۰۰۰+</span>
                    <span class="stat-label">خواننده</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container app-container">
    <!-- Post Categories Filter -->
    <section class="post-filters">
        <div class="filter-buttons">
            <button class="filter-btn active btn btn-soft" data-filter="all">همه مقالات</button>
            <button class="filter-btn btn btn-soft" data-filter="grammar">گرامر</button>
            <button class="filter-btn btn btn-soft" data-filter="vocabulary">واژگان</button>
            <button class="filter-btn btn btn-soft" data-filter="conversation">مکالمه</button>
            <button class="filter-btn btn btn-soft" data-filter="exam">آزمون‌ها</button>
            <button class="filter-btn btn btn-soft" data-filter="tips">نکات آموزشی</button>
        </div>
    </section>

    <!-- Main Posts Section -->
    <section class="posts-section">
        <div class="section-header">
            <h2>مقالات آموزشی ما</h2>
            <p>انتخاب کنید و از جدیدترین مطالب آموزشی بهره‌مند شوید</p>
        </div>
        
        <div class="posts-grid grid">
            <?php foreach ($posts as $post): ?>
                <?php $postImg = resolveImagePath($post['image_url'] ?? null, 'assets/images/default-post.jpg'); ?>
                <article class="post-card card col-4" data-category="grammar">
                    <div class="post-header">
                        <div class="post-category">گرامر</div>
                        <div class="post-date">
                            <i class="fas fa-calendar"></i>
                            <span><?= date('Y/m/d', strtotime($post['created_at'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="post-avatar">
                        <img src="<?= htmlspecialchars($postImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    </div>

                    <div class="post-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars(substr(strip_tags($post['content']), 0, 120)) ?>...</p>
                        
                        <div class="post-meta">
                            <div class="author-info">
                                <div class="teacher-avatar">
                                    <img src="<?= resolveImagePath($post['instructor_photo'] ?? null, 'assets/images/default-teacher.jpg') ?>" alt="<?= htmlspecialchars($post['instructor_name']) ?>">
                                </div>
                                <div class="author-details">
                                    <span class="author-name"><?= htmlspecialchars($post['instructor_name']) ?></span>
                                    <span class="author-title">استاد زبان انگلیسی</span>
                                </div>
                            </div>
                            
                            <div class="post-stats">
                                <div class="stat">
                                    <i class="fas fa-eye"></i>
                                    <span><?= rand(50, 500) ?></span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-comment"></i>
                                    <span><?= count($post['comments']) ?></span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-heart"></i>
                                    <span><?= rand(10, 100) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="post-tags">
                            <span class="tag">گرامر</span>
                            <span class="tag">آموزش</span>
                            <span class="tag">نکات</span>
                        </div>
                        
                        <div class="post-actions">
                            <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-soft btn-read-more">ادامه مطلب</a>
                            <button class="btn-bookmark btn btn-soft" style="width:42px;height:42px;padding:0;">
                                <i class="far fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Posts Section -->
    <section class="featured-posts-section section">
        <div class="section-header">
            <h2>مقالات ویژه</h2>
            <p>پربازدیدترین و محبوب‌ترین مقالات ما</p>
        </div>
        
        <div class="featured-posts-grid grid">
            <div class="featured-post-card large">
                <div class="post-category">ویژه</div>
                <h3>۱۰ نکته کلیدی برای یادگیری سریع زبان انگلیسی</h3>
                <p>در این مقاله، ۱۰ نکته کاربردی و موثر برای یادگیری سریع‌تر زبان انگلیسی را بررسی می‌کنیم...</p>
                <div class="post-meta">
                    <span class="author">دکتر احمدی</span>
                    <span class="date">۲ روز پیش</span>
                    <span class="views">۲,۵۰۰ بازدید</span>
                </div>
                <a href="#" class="btn btn-primary btn-read-more">ادامه مطلب</a>
            </div>
            
            <div class="featured-post-card">
                <div class="post-category">گرامر</div>
                <h3>آموزش کامل زمان‌های انگلیسی</h3>
                <p>راهنمای جامع تمام زمان‌های زبان انگلیسی با مثال‌های کاربردی...</p>
                <div class="post-meta">
                    <span class="author">خانم محمدی</span>
                    <span class="date">۱ هفته پیش</span>
                </div>
                <a href="#" class="btn btn-soft btn-read-more">ادامه مطلب</a>
            </div>
            
            <div class="featured-post-card">
                <div class="post-category">مکالمه</div>
                <h3>اصطلاحات روزمره انگلیسی</h3>
                <p>مجموعه‌ای از پرکاربردترین اصطلاحات انگلیسی در مکالمات روزمره...</p>
                <div class="post-meta">
                    <span class="author">آقای رضایی</span>
                    <span class="date">۳ روز پیش</span>
                </div>
                <a href="#" class="btn btn-soft btn-read-more">ادامه مطلب</a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section for Posts -->
    <section class="posts-newsletter section">
        <div class="newsletter-content">
            <h3>از جدیدترین مقالات باخبر شوید</h3>
            <p>با عضویت در خبرنامه، جدیدترین مقالات آموزشی را در ایمیل خود دریافت کنید</p>
            <form class="newsletter-form">
                <input type="email" placeholder="آدرس ایمیل شما" required>
                <button type="submit" class="btn btn-primary">عضویت</button>
            </form>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>
