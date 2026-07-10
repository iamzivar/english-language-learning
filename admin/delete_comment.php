<?php
session_start();
include '../includes/database.php';

// نمایش صفحه تأیید حذف
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $commentId = $_GET['id'];
    
    try {
        // دریافت اطلاعات کامنت برای نمایش به کاربر
        $stmt = $conn->prepare("SELECT c.*, u.username, p.title 
                              FROM comments c
                              LEFT JOIN users u ON c.user_id = u.id
                              LEFT JOIN posts p ON c.post_id = p.id
                              WHERE c.id = ?");
        $stmt->execute([$commentId]);
        $comment = $stmt->fetch();

        if (!$comment) {
            die("کامنت مورد نظر یافت نشد");
        }

        // نمایش فرم تأیید
        ?>
        <!DOCTYPE html>
        <html lang="fa" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>حذف نظر</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            <link rel="stylesheet" href="assets/css/admin.css">
        </head>
        <body>
            <div class="admin-header">
                <h1>حذف نظر</h1>
            </div>

            <div class="admin-container">
                <?php include 'includes/admin_sidebar.php'; ?>

                <main class="main-content">
                    <section class="data-section">
                        <h2 class="section-title">تأیید حذف نظر</h2>

                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <p>آیا مطمئن هستید می‌خواهید این نظر را حذف کنید؟</p>
                                    <hr>
                                    <p><strong><i class="bi bi-person"></i> کاربر:</strong> <?= htmlspecialchars($comment['username']) ?></p>
                                    <p><strong><i class="bi bi-file-text"></i> پست:</strong> <?= htmlspecialchars($comment['title']) ?></p>
                                    <p><strong><i class="bi bi-chat"></i> نظر:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                                </div>
                                
                                <form method="POST" action="delete_comment.php?id=<?= $commentId ?>">
                                    <div class="section-actions">
                                        <button type="submit" class="btn btn-delete">
                                            <i class="bi bi-trash"></i> حذف نظر
                                        </button>
                                        <a href="admin_comments.php" class="btn btn-view-all">
                                            <i class="bi bi-arrow-left"></i> انصراف
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </main>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
        exit();
    } catch (PDOException $e) {
        die("خطا در ارتباط با پایگاه داده: " . $e->getMessage());
    }
}

// پردازش حذف پس از تأیید
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $commentId = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        if ($stmt->execute([$commentId])) {
            $_SESSION['success_message'] = 'نظر با موفقیت حذف شد';
            header('Location: admin_comments.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'خطا در حذف نظر: ' . $e->getMessage();
        header('Location: admin_comments.php');
        exit();
    }
}

// اگر پارامتر id وجود نداشته باشد
header('Location: admin_comments.php');
exit();
?>