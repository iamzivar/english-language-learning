<?php
session_start();
include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $userId = $_GET['id'] ?? null;
    
    if ($userId) {
        try {
            // بررسی آیا کاربر در حال حذف خودش نیست
            if ($userId == $_SESSION['user_id']) {
                $_SESSION['error_message'] = 'شما نمی‌توانید حساب خودتان را حذف کنید';
                header('Location: admin_users.php');
                exit();
            }

            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$userId])) {
                $_SESSION['success_message'] = 'کاربر با موفقیت حذف شد';
                header('Location: admin_users.php');
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'خطا در حذف کاربر: ' . $e->getMessage();
            header('Location: admin_users.php');
            exit();
        }
    }
} elseif (isset($_GET['id'])) {
    // نمایش صفحه تأیید حذف
    $userId = $_GET['id'];
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error_message'] = 'کاربر مورد نظر یافت نشد';
        header('Location: admin_users.php');
        exit();
    }

    ?>
    
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>حذف کاربر</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/admin.css">
    </head>
    <body>
        <div class="admin-header">
            <h1>حذف کاربر</h1>
        </div>

        <div class="admin-container">
            <?php include 'includes/admin_sidebar.php'; ?>

            <main class="main-content">
                <section class="data-section">
                    <h2 class="section-title">تأیید حذف کاربر</h2>

                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                آیا مطمئن هستید می‌خواهید کاربر "<strong><?= htmlspecialchars($user['username']) ?></strong>" را حذف کنید؟
                                <br><br>
                                <strong>توجه:</strong> تمام داده‌های مرتبط با این کاربر نیز حذف خواهند شد!
                            </div>
                            
                            <form method="POST">
                                <input type="hidden" name="confirm_delete" value="1">
                                <div class="section-actions">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="bi bi-trash"></i> حذف کاربر
                                    </button>
                                    <a href="admin_users.php" class="btn btn-view-all">
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
}

header('Location: admin_users.php');
exit();
?>