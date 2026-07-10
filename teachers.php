<?php
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

// دریافت تمام اساتید با میانگین امتیاز
$stmt_teachers = $conn->prepare("
    SELECT i.id, i.name, i.bio, i.photo_url, 
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as rating_count
    FROM instructors i
    LEFT JOIN ratings r ON i.id = r.instructor_id
    GROUP BY i.id
    ORDER BY avg_rating DESC, rating_count DESC
");
$stmt_teachers->execute();
$teachers = $stmt_teachers->fetchAll();
?>

<!-- Hero Section for Teachers -->
<section class="teachers-hero hero">
    <div class="teachers-hero-bg"></div>
    <div class="container app-container surface">
        <div class="teachers-hero-content">
            <h1>اساتید مجرب و متخصص</h1>
            <p>با بهترین اساتید زبان انگلیسی با تجربه و مدارک بین‌المللی آشنا شوید</p>
            <div class="teachers-hero-stats stack-md">
                <div class="stat-item">
                    <span class="stat-number">۱۵+</span>
                    <span class="stat-label">استاد مجرب</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۱۰+</span>
                    <span class="stat-label">سال تجربه</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">۴.۸</span>
                    <span class="stat-label">میانگین امتیاز</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container app-container">
    <!-- Teacher Filters -->
    <section class="teacher-filters">
        <div class="filter-buttons">
            <button class="filter-btn active btn btn-soft" data-filter="all">همه اساتید</button>
            <button class="filter-btn btn btn-soft" data-filter="top-rated">برترین اساتید</button>
            <button class="filter-btn btn btn-soft" data-filter="experienced">با تجربه</button>
            <button class="filter-btn btn btn-soft" data-filter="new">اساتید جدید</button>
        </div>
    </section>

    <!-- Main Teachers Section -->
    <section class="teachers-section">
        <div class="section-header">
            <h2>اساتید مجرب ما</h2>
            <p>انتخاب کنید و با بهترین اساتید یادگیری را شروع کنید</p>
        </div>
        
        <div class="teachers-grid grid">
            <?php foreach ($teachers as $teacher): ?>
                <?php $teacherImg = resolveImagePath($teacher['photo_url'] ?? null, 'assets/images/default-teacher.jpg'); ?>
                <div class="teacher-card card col-4" data-rating="<?= $teacher['avg_rating'] ?>" data-experience="<?= $teacher['rating_count'] ?>">
                    <div class="teacher-header">
                        <div class="teacher-avatar">
                            <img src="<?= htmlspecialchars($teacherImg) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>">
                            <div class="teacher-status">
                                <i class="fas fa-circle"></i>
                            </div>
                        </div>
                        <div class="teacher-info">
                            <h3><?= htmlspecialchars($teacher['name']) ?></h3>
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
                        </div>
                    </div>
                    
                    <div class="teacher-bio">
                        <p><?= htmlspecialchars(substr($teacher['bio'], 0, 120)) ?>...</p>
                    </div>
                    
                    <div class="teacher-stats">
                        <div class="stat">
                            <i class="fas fa-users"></i>
                            <span><?= $teacher['rating_count'] ?> دانشجو</span>
                        </div>
                        <div class="stat">
                            <i class="fas fa-clock"></i>
                            <span><?= rand(3, 8) ?> سال تجربه</span>
                        </div>
                        <div class="stat">
                            <i class="fas fa-graduation-cap"></i>
                            <span><?= rand(5, 15) ?> دوره</span>
                        </div>
                    </div>
                    
                    <div class="teacher-specialties">
                        <span class="specialty">گرامر</span>
                        <span class="specialty">مکالمه</span>
                        <span class="specialty">آزمون‌ها</span>
                    </div>
                    
                    <div class="teacher-actions">
                        <a href="teacher-profile.php?id=<?= $teacher['id'] ?>" class="btn btn-primary">مشاهده پروفایل</a>
                        <button class="btn btn-accent btn-contact">تماس</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Why Choose Our Teachers Section -->
    <section class="why-teachers-section section">
        <div class="section-header">
            <h2>چرا اساتید ما؟</h2>
            <p>ویژگی‌های منحصر به فرد که اساتید ما را متمایز می‌کند</p>
        </div>
        
        <div class="features-grid grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>مدارک معتبر</h3>
                <p>تمام اساتید دارای مدارک بین‌المللی و معتبر هستند</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>تجربه بالا</h3>
                <p>حداقل ۵ سال تجربه تدریس در موسسات معتبر</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>امتیاز عالی</h3>
                <p>میانگین امتیاز بالای ۴.۵ از ۵ از دانشجویان</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>تعداد دانشجو</h3>
                <p>هر استاد حداقل ۵۰ دانشجو موفق داشته است</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>متدهای نوین</h3>
                <p>استفاده از جدیدترین متدهای آموزشی روز دنیا</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>پشتیبانی کامل</h3>
                <p>پشتیبانی ۲۴/۷ از دانشجویان در تمام ساعات</p>
            </div>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>