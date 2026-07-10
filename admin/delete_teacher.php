<?php
session_start();
include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $teacherId = $_GET['id'] ?? null;
    
    if ($teacherId) {
        try {
            // حذف تصویر استاد اگر وجود دارد
            $stmt = $conn->prepare("SELECT image_url FROM instructors WHERE id = ?");
            $stmt->execute([$teacherId]);
            $teacher = $stmt->fetch();
            
            if ($teacher && $teacher['image_url']) {
                $imagePath = "../assets/images/teachers/" . $teacher['image_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // حذف استاد از دیتابیس
            $stmt = $conn->prepare("DELETE FROM instructors WHERE id = ?");
            $stmt->execute([$teacherId]);
            
            $_SESSION['success_message'] = 'استاد با موفقیت حذف شد';
            header('Location: admin_teachers.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "خطا در حذف استاد: " . $e->getMessage();
            header('Location: admin_teachers.php');
            exit();
        }
    }
} elseif (isset($_GET['id'])) {
    // نمایش صفحه تأیید حذف
    $teacherId = $_GET['id'];
    $stmt = $conn->prepare("SELECT name FROM instructors WHERE id = ?");
    $stmt->execute([$teacherId]);
    $teacher = $stmt->fetch();

    if (!$teacher) {
        die("استاد مورد نظر یافت نشد");
    }

    ?>
    
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>حذف استاد</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/admin.css">
    </head>
    <body>
        <div class="admin-header">
            <h1>حذف استاد</h1>
        </div>

        <div class="admin-container">
            <?php include 'includes/admin_sidebar.php'; ?>

            <main class="main-content">
                <section class="data-section">
                    <h2 class="section-title">تأیید حذف استاد</h2>

                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                آیا مطمئن هستید می‌خواهید استاد "<strong><?= htmlspecialchars($teacher['name']) ?></strong>" را حذف کنید؟
                                <br><small class="text-muted">این عملیات غیرقابل بازگشت است.</small>
                            </div>
                            
                            <form method="POST">
                                <input type="hidden" name="confirm_delete" value="1">
                                <div class="section-actions">
                                    <button type="submit" class="btn btn-delete">
                                        <i class="bi bi-trash"></i> حذف استاد
                                    </button>
                                    <a href="admin_teachers.php" class="btn btn-view-all">
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

header('Location: admin_teachers.php');
exit();
?>