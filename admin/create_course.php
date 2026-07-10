<?php
session_start();

// // بررسی آیا کاربر لاگین کرده و مدیر است
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $level = $_POST['level'];
    $duration = $_POST['duration'];
    $lessons_count = $_POST['lessons_count'];
    $price = $_POST['price'];
    $instructor_id = $_POST['instructor_id'] ?? null;
    
    // اعتبارسنجی داده‌ها
    if (empty($title) || empty($description) || empty($level) || empty($duration) || empty($lessons_count)) {
        $error = "لطفاً تمام فیلدهای ضروری را پر کنید";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO courses (title, description, level, duration, lessons_count, price, instructor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $description, $level, $duration, $lessons_count, $price, $instructor_id])) {
                $_SESSION['success_message'] = "دوره با موفقیت ایجاد شد!";
                header('Location: admin_courses.php');
                exit();
            }
        } catch (PDOException $e) {
            $error = "خطا در ایجاد دوره: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایجاد دوره جدید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ایجاد دوره جدید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ایجاد دوره</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-book"></i> عنوان دوره:
                                </label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i> توضیحات:
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">
                                            <i class="bi bi-bar-chart"></i> سطح دوره:
                                        </label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="">انتخاب کنید</option>
                                            <option value="beginner">مبتدی</option>
                                            <option value="intermediate">متوسط</option>
                                            <option value="advanced">پیشرفته</option>
                                            <option value="exam">آزمون</option>
                                            <option value="business">تجاری</option>
                                            <option value="conversation">مکالمه</option>
                                            <option value="grammar">گرامر</option>
                                            <option value="writing">نگارش</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">
                                            <i class="bi bi-clock"></i> مدت زمان:
                                        </label>
                                        <input type="text" class="form-control" id="duration" name="duration" placeholder="مثال: ۸ هفته" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lessons_count" class="form-label">
                                            <i class="bi bi-play-circle"></i> تعداد درس:
                                        </label>
                                        <input type="number" class="form-control" id="lessons_count" name="lessons_count" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <i class="bi bi-currency-exchange"></i> قیمت (تومان):
                                        </label>
                                        <input type="number" class="form-control" id="price" name="price" min="0" step="1000" value="0">
                                        <small class="form-text text-muted">برای دوره‌های رایگان صفر وارد کنید</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="instructor_id" class="form-label">
                                    <i class="bi bi-person-video3"></i> استاد مربوطه:
                                </label>
                                <select class="form-select" id="instructor_id" name="instructor_id">
                                    <option value="">بدون استاد</option>
                                    <?php
                                    $instructors = $conn->query("SELECT id, name FROM instructors");
                                    foreach ($instructors as $instructor): ?>
                                        <option value="<?= $instructor['id'] ?>"><?= htmlspecialchars($instructor['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-add-new">
                                    <i class="bi bi-check-circle"></i> ایجاد دوره
                                </button>
                                <a href="admin_courses.php" class="btn btn-view-all">
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
