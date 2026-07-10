<?php
session_start();
include '../includes/database.php';

$stmt = $conn->prepare("SELECT c.*, i.name AS instructor_name 
                      FROM courses c
                      LEFT JOIN instructors i ON c.instructor_id = i.id
                      ORDER BY c.created_at DESC");
$stmt->execute();
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت دوره‌ها</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت دوره‌ها</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست دوره‌ها</h2>
                
                <div class="section-actions">
                    <a href="create_course.php" class="btn btn-add-new">
                        <i class="bi bi-plus-circle"></i> ایجاد دوره جدید
                    </a>
                </div>

                <?php
                // نمایش پیام‌های سیستم
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success">'.$_SESSION['success_message'].'</div>';
                    unset($_SESSION['success_message']);
                }
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger">'.$_SESSION['error_message'].'</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>سطح</th>
                                <th>مدت زمان</th>
                                <th>تعداد درس</th>
                                <th>قیمت</th>
                                <th>امتیاز</th>
                                <th>استاد مربوطه</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= $course['id'] ?></td>
                                <td><?= htmlspecialchars($course['title']) ?></td>
                                <td>
                                    <span class="badge bg-<?= getLevelBadgeColor($course['level']) ?>">
                                        <?= getLevelPersianName($course['level']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($course['duration']) ?></td>
                                <td><?= $course['lessons_count'] ?></td>
                                <td>
                                    <?php if ($course['price'] > 0): ?>
                                        <?= number_format($course['price']) ?> تومان
                                    <?php else: ?>
                                        <span class="text-success">رایگان</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['rating'] > 0): ?>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning me-1">
                                                <?= number_format($course['rating'], 1) ?>
                                            </span>
                                            <small class="text-muted">(<?= $course['rating_count'] ?>)</small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">بدون امتیاز</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($course['instructor_name'] ?? 'بدون استاد') ?></td>
                                <td class="actions">
                                    <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </a>
                                    <a href="delete_course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('آیا مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
function getLevelBadgeColor($level) {
    $colors = [
        'beginner' => 'success',
        'intermediate' => 'warning',
        'advanced' => 'danger',
        'exam' => 'info',
        'business' => 'primary',
        'conversation' => 'secondary',
        'grammar' => 'dark',
        'writing' => 'purple'
    ];
    return $colors[$level] ?? 'secondary';
}

function getLevelPersianName($level) {
    $names = [
        'beginner' => 'مبتدی',
        'intermediate' => 'متوسط',
        'advanced' => 'پیشرفته',
        'exam' => 'آزمون',
        'business' => 'تجاری',
        'conversation' => 'مکالمه',
        'grammar' => 'گرامر',
        'writing' => 'نگارش'
    ];
    return $names[$level] ?? $level;
}
?>
