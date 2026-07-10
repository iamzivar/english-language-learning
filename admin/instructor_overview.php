<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

$instructorId = isset($_GET['instructor_id']) ? (int)$_GET['instructor_id'] : 0;
if ($instructorId <= 0) {
    header('Location: admin_instructors_report.php');
    exit;
}

// Instructor info
$stmt = $conn->prepare("SELECT id, name, photo_url FROM instructors WHERE id=?");
$stmt->execute([$instructorId]);
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$instructor) {
    header('Location: admin_instructors_report.php');
    exit;
}

// Courses by instructor
$courses = $conn->prepare("SELECT id, title, category, level, price FROM courses WHERE instructor_id=? ORDER BY created_at DESC");
$courses->execute([$instructorId]);
$coursesList = $courses->fetchAll(PDO::FETCH_ASSOC);

// Unique students taught
$studentsStmt = $conn->prepare("SELECT COUNT(DISTINCT e.user_id) AS students_count
                                FROM enrollments e
                                JOIN courses c ON c.id = e.course_id
                                WHERE c.instructor_id = ? AND e.status='active'");
$studentsStmt->execute([$instructorId]);
$studentsCount = (int)$studentsStmt->fetchColumn();

// Average rating
$avgStmt = $conn->prepare("SELECT ROUND(AVG(rating),2) FROM ratings WHERE instructor_id=?");
$avgStmt->execute([$instructorId]);
$avgRating = $avgStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نمای کلی استاد - <?= htmlspecialchars($instructor['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>نمای کلی استاد</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <div class="d-flex align-items-center mb-3">
                    <img src="../<?= htmlspecialchars($instructor['photo_url'] ?: 'assets/images/default-teacher.jpg') ?>" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin-left:12px;">
                    <div>
                        <h2 class="section-title" style="margin:0;"><?= htmlspecialchars($instructor['name']) ?></h2>
                        <div class="mt-1">
                            <span class="badge bg-primary">دوره‌ها: <?= count($coursesList) ?></span>
                            <span class="badge bg-success">دانشجویان: <?= $studentsCount ?></span>
                            <span class="badge bg-warning text-dark">میانگین امتیاز: <?= $avgRating !== null ? number_format($avgRating,2) : '—' ?></span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th># دوره</th>
                                <th>عنوان دوره</th>
                                <th>دسته‌بندی</th>
                                <th>سطح</th>
                                <th>قیمت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($coursesList) === 0): ?>
                            <tr>
                                <td colspan="5">هیچ دوره‌ای ثبت نشده است.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($coursesList as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td><?= htmlspecialchars($c['title']) ?></td>
                                    <td><?= htmlspecialchars($c['category']) ?></td>
                                    <td><?= htmlspecialchars($c['level']) ?></td>
                                    <td><?= htmlspecialchars($c['price']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="admin_instructors_report.php" class="btn btn-secondary">بازگشت به گزارش اساتید</a>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


