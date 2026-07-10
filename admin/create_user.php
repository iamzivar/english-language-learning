
<?php
session_start();
include '../includes/database.php';

// غیرفعال کردن بررسی سطح دسترسی (کامنت شده)
/*
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
*/

$error = null;

// پردازش فرم ایجاد کاربر
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // اعتبارسنجی داده‌ها
    if (empty($username) || empty($email) || empty($password)) {
        $error = "لطفاً تمام فیلدهای ضروری را پر کنید";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashedPassword, $role])) {
                $_SESSION['success_message'] = "کاربر جدید با موفقیت ایجاد شد";
                header('Location: admin_users.php');
                exit();
            }
        } catch (PDOException $e) {
            $error = "خطا در ایجاد کاربر: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایجاد کاربر جدید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ایجاد کاربر جدید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ایجاد کاربر</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> نام کاربری:
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> ایمیل:
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> رمز عبور:
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="bi bi-shield"></i> نقش کاربر:
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user">کاربر عادی</option>
                                    <option value="admin">مدیر</option>
                                </select>
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-add-new">
                                    <i class="bi bi-check-circle"></i> ایجاد کاربر
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