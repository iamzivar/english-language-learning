<?php
session_start();
include '../includes/database.php';

if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch();

    if (!$course) {
        die("دوره یافت نشد.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $level = $_POST['level'];
    $duration = $_POST['duration'];
    $lessons_count = $_POST['lessons_count'];
    $price = $_POST['price'];
    $instructor_id = $_POST['instructor_id'] ?? null;

    try {
        $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ?, level = ?, duration = ?, lessons_count = ?, price = ?, instructor_id = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $level, $duration, $lessons_count, $price, $instructor_id, $courseId])) {
            $_SESSION['success_message'] = "دوره با موفقیت ویرایش شد!";
            header('Location: admin_courses.php');
            exit();
        }
    } catch (PDOException $e) {
        $error = "خطا در ویرایش دوره: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش دوره</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ویرایش دوره</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ویرایش دوره</h2>

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
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?= htmlspecialchars($course['title'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i> توضیحات:
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">
                                            <i class="bi bi-bar-chart"></i> سطح دوره:
                                        </label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="">انتخاب کنید</option>
                                            <option value="beginner" <?= ($course['level'] ?? '') == 'beginner' ? 'selected' : '' ?>>مبتدی</option>
                                            <option value="intermediate" <?= ($course['level'] ?? '') == 'intermediate' ? 'selected' : '' ?>>متوسط</option>
                                            <option value="advanced" <?= ($course['level'] ?? '') == 'advanced' ? 'selected' : '' ?>>پیشرفته</option>
                                            <option value="exam" <?= ($course['level'] ?? '') == 'exam' ? 'selected' : '' ?>>آزمون</option>
                                            <option value="business" <?= ($course['level'] ?? '') == 'business' ? 'selected' : '' ?>>تجاری</option>
                                            <option value="conversation" <?= ($course['level'] ?? '') == 'conversation' ? 'selected' : '' ?>>مکالمه</option>
                                            <option value="grammar" <?= ($course['level'] ?? '') == 'grammar' ? 'selected' : '' ?>>گرامر</option>
                                            <option value="writing" <?= ($course['level'] ?? '') == 'writing' ? 'selected' : '' ?>>نگارش</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">
                                            <i class="bi bi-clock"></i> مدت زمان:
                                        </label>
                                        <input type="text" class="form-control" id="duration" name="duration" 
                                               value="<?= htmlspecialchars($course['duration'] ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lessons_count" class="form-label">
                                            <i class="bi bi-play-circle"></i> تعداد درس:
                                        </label>
                                        <input type="number" class="form-control" id="lessons_count" name="lessons_count" 
                                               value="<?= $course['lessons_count'] ?? '' ?>" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <i class="bi bi-currency-exchange"></i> قیمت (تومان):
                                        </label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               value="<?= $course['price'] ?? '0' ?>" min="0" step="1000">
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
                                        <option value="<?= $instructor['id'] ?>" <?= ($course['instructor_id'] ?? '') == $instructor['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($instructor['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-edit">
                                    <i class="bi bi-check-circle"></i> ذخیره تغییرات
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
