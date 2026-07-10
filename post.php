<?php
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$post_id = $_GET['id'];

// دریافت اطلاعات پست
$stmt_post = $conn->prepare("
    SELECT p.id, p.title, p.content, p.created_at, p.image_url,
           i.name as instructor_name, i.photo_url as instructor_photo
    FROM posts p
    JOIN instructors i ON p.instructor_id = i.id
    WHERE p.id = ?
");
$stmt_post->execute([$post_id]);
$post = $stmt_post->fetch();

if (!$post) {
    header("Location: posts.php");
    exit();
}

// دریافت کامنت‌های پست
$stmt_comments = $conn->prepare("
    SELECT c.content, c.created_at, u.username
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = ? AND c.status = 1
    ORDER BY c.created_at DESC
");
$stmt_comments->execute([$post_id]);
$comments = $stmt_comments->fetchAll();

$postImg = resolveImagePath($post['image_url'] ?? null, 'assets/images/default-post.jpg');
?>

<div class="container app-container">
    <article class="single-post">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        
        <div class="post-meta-info">
            <span class="author">نویسنده: <?= htmlspecialchars($post['instructor_name']) ?></span>
            <span class="date">تاریخ: <?= htmlspecialchars($post['created_at']) ?></span>
        </div>
        
        <?php if ($postImg): ?>
            <div class="post-image-container">
                <img src="<?= htmlspecialchars($postImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-main-image">
            </div>
        <?php endif; ?>
        
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
    </article>
    
    <!-- بخش کامنت‌ها -->
    <section class="comments-section section" id="comments">
        <h2>نظرات کاربران (<?= count($comments) ?>)</h2>
        
        <?php if (!empty($comments)): ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <strong class="comment-author"><?= htmlspecialchars($comment['username']) ?></strong>
                            <span class="comment-date"><?= htmlspecialchars($comment['created_at']) ?></span>
                        </div>
                        <div class="comment-content">
                            <?= htmlspecialchars($comment['content']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-comments">هنوز نظری ثبت نشده است.</p>
        <?php endif; ?>
        
        <!-- فرم ارسال کامنت -->
        <div class="comment-form surface">
            <h3>ارسال نظر</h3>
            <form action="comment.php" method="POST">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <div class="form-group">
                    <textarea name="content" class="textarea" placeholder="نظر خود را بنویسید..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">ارسال نظر</button>
            </form>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>
