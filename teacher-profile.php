image.png<?php
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

if (!isset($_GET['id'])) {
    header("Location: teachers.php");
    exit();
}

$teacher_id = $_GET['id'];

// دریافت اطلاعات استاد
$stmt_teacher = $conn->prepare("
    SELECT i.id, i.name, i.bio, i.photo_url, i.created_at,
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as rating_count
    FROM instructors i
    LEFT JOIN ratings r ON i.id = r.instructor_id
    WHERE i.id = ?
    GROUP BY i.id
");
$stmt_teacher->execute([$teacher_id]);
$teacher = $stmt_teacher->fetch();

if (!$teacher) {
    header("Location: teachers.php");
    exit();
}

// دریافت پست‌های استاد
$stmt_posts = $conn->prepare("
    SELECT p.id, p.title, p.content, p.created_at, p.image_url
    FROM posts p
    WHERE p.instructor_id = ?
    ORDER BY p.created_at DESC
    LIMIT 5
");
$stmt_posts->execute([$teacher_id]);
$posts = $stmt_posts->fetchAll();

// دریافت نظرات استاد
$stmt_ratings = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at, u.username
    FROM ratings r
    JOIN users u ON r.user_id = u.id
    WHERE r.instructor_id = ?
    ORDER BY r.created_at DESC
    LIMIT 10
");
$stmt_ratings->execute([$teacher_id]);
$ratings = $stmt_ratings->fetchAll();

$teacherImg = resolveImagePath($teacher['photo_url'] ?? null, 'assets/images/default-teacher.jpg');
?>

<div class="container">
    <section class="teacher-profile">
        <div class="teacher-header">
            <div class="teacher-image">
                <img src="<?= htmlspecialchars($teacherImg) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>">
            </div>
            <div class="teacher-info">
                <h1><?= htmlspecialchars($teacher['name']) ?></h1>
                <p class="teacher-bio"><?= htmlspecialchars($teacher['bio']) ?></p>
                
                <div class="teacher-stats">
                    <div class="stat">
                        <span class="stat-number"><?= number_format($teacher['avg_rating'], 1) ?></span>
                        <span class="stat-label">میانگین امتیاز</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?= $teacher['rating_count'] ?></span>
                        <span class="stat-label">تعداد نظرات</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?= count($posts) ?></span>
                        <span class="stat-label">مقالات</span>
                    </div>
                </div>
                
                <div class="rating-stars">
                    <?php
                    $fullStars = floor($teacher['avg_rating']);
                    $halfStar = ($teacher['avg_rating'] - $fullStars) >= 0.5;
                    
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
                    <span>(<?= $teacher['rating_count'] ?> رأی)</span>
                </div>
            </div>
        </div>
        
        <!-- فرم امتیازدهی -->
        <div class="rating-section">
            <h3>امتیازدهی به استاد</h3>
            <form action="rate.php" method="POST" class="rating-form">
                <input type="hidden" name="instructor_id" value="<?= $teacher_id ?>">
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                </div>
                <button type="submit" class="btn btn-primary">ارسال امتیاز</button>
            </form>
        </div>
        
        <!-- مقالات استاد -->
        <?php if (!empty($posts)): ?>
            <section class="teacher-posts">
                <h3>مقالات <?= htmlspecialchars($teacher['name']) ?></h3>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <?php $postImg = resolveImagePath($post['image_url'] ?? null, 'assets/images/default-post.jpg'); ?>
                        <article class="post-card">
                            <div class="post-image">
                                <img src="<?= htmlspecialchars($postImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                            </div>
                            <div class="post-content">
                                <h4><?= htmlspecialchars($post['title']) ?></h4>
                                <p><?= htmlspecialchars(substr(strip_tags($post['content']), 0, 100)) ?>...</p>
                                <a href="post.php?id=<?= $post['id'] ?>" class="read-more">ادامه مطلب</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- نظرات کاربران -->
        <?php if (!empty($ratings)): ?>
            <section class="teacher-reviews">
                <h3>نظرات کاربران</h3>
                <div class="reviews-list">
                    <?php foreach ($ratings as $rating): ?>
                        <div class="review">
                            <div class="review-header">
                                <strong><?= htmlspecialchars($rating['username']) ?></strong>
                                <div class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $rating['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span class="review-date"><?= htmlspecialchars($rating['created_at']) ?></span>
                            </div>
                            <?php if ($rating['comment']): ?>
                                <p><?= htmlspecialchars($rating['comment']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </section>
</div>

<?php include 'layout/footer.php'; ?>
