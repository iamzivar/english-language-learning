<?php
session_start();

// // بررسی آیا کاربر لاگین کرده و مدیر است
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

include '../includes/database.php';

// بررسی آیا درخواست از نوع POST است (برای امنیت بیشتر)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $postId = $_GET['id'] ?? null;
    
    if ($postId) {
        try {
            // حذف پست
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            
            $_SESSION['message'] = 'پست با موفقیت حذف شد';
            header('Location: admin_posts.php');
            exit();
        } catch (PDOException $e) {
            die("خطا در حذف پست: " . $e->getMessage());
        }
    }
} elseif (isset($_GET['id'])) {
    // نمایش صفحه تایید حذف
    $postId = $_GET['id'];
    $stmt = $conn->prepare("SELECT title FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();

    if (!$post) {
        die("پست مورد نظر یافت نشد");
    }

    // نمایش فرم تایید
    ?>
    
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>حذف پست</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/admin.css">
    </head>
    <body>
        <div class="admin-header">
            <h1>حذف پست</h1>
        </div>

        <div class="admin-container">
            <?php include 'includes/admin_sidebar.php'; ?>

            <main class="main-content">
                <section class="data-section">
                    <h2 class="section-title">تأیید حذف پست</h2>

                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                آیا مطمئن هستید می‌خواهید پست "<strong><?= htmlspecialchars($post['title']) ?></strong>" را حذف کنید؟
                                <br><small class="text-muted">این عملیات غیرقابل بازگشت است.</small>
                            </div>
                            
                            <form method="POST">
                                <input type="hidden" name="confirm_delete" value="1">
                                <div class="section-actions">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="bi bi-trash"></i> حذف پست
                                    </button>
                                    <a href="admin_posts.php" class="btn btn-view-all">
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
} else {
    header('Location: admin_posts.php');
    exit();
}
?>