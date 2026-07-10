<?php
session_start();
include '../includes/database.php';

// غیرفعال کردن بررسی سطح دسترسی (برای محیط توسعه)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

// مقداردهی اولیه متغیرها
$user = null;
$error = null;

// دریافت اطلاعات کاربر برای ویرایش
if (isset($_GET['id'])) {
    $userId = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error_message'] = 'کاربر مورد نظر یافت نشد';
        header('Location: admin_users.php');
        exit();
    }
}

// پردازش فرم ویرایش
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = (int)$_POST['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // اعتبارسنجی داده‌ها
    if (empty($username) || empty($email)) {
        $error = "لطفاً تمام فیلدهای ضروری را پر کنید";
    } else {
        try {
            // اگر رمز عبور تغییر کرده باشد
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $email, $role, $hashedPassword, $userId]);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $email, $role, $userId]);
            }

            $_SESSION['success_message'] = 'اطلاعات کاربر با موفقیت به‌روزرسانی شد';
            header('Location: admin_users.php');
            exit();
        } catch (PDOException $e) {
            $error = "خطا در به‌روزرسانی کاربر: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش کاربر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ویرایش کاربر</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ویرایش کاربر</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if ($user): ?>
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> نام کاربری:
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> ایمیل:
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="bi bi-shield"></i> نقش کاربر:
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>کاربر عادی</option>
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>مدیر</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> رمز عبور جدید (اختیاری):
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="برای حفظ رمز عبور فعلی خالی بگذارید">
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-edit">
                                    <i class="bi bi-check-circle"></i> ذخیره تغییرات
                                </button>
                                <a href="admin_users.php" class="btn btn-view-all">
                                    <i class="bi bi-arrow-left"></i> انصراف
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> کاربر مورد نظر یافت نشد
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>