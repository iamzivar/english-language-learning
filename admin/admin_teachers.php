<?php
session_start();
include '../includes/database.php';

// Get teachers data
try {
    $stmt = $conn->prepare("SELECT * FROM instructors ORDER BY id DESC");
    $stmt->execute();
    $teachers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("خطا در دریافت اطلاعات اساتید: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت اساتید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت اساتید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست اساتید</h2>
                
                <div class="section-actions">
                    <a href="create_teacher.php" class="btn btn-add-new">
                        <i class="bi bi-plus-circle"></i> اضافه کردن استاد جدید
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
                                <th>نام استاد</th>
                                <th>بیو</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($teachers)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="bi bi-info-circle"></i> هیچ استادی یافت نشد.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <tr>
                                        <td><?= $teacher['id'] ?></td>
                                        <td><?= htmlspecialchars($teacher['name']) ?></td>
                                        <td><?= htmlspecialchars(substr($teacher['bio'], 0, 100)) ?>...</td>
                                        <td class="actions">
                                            <a href="edit_teacher.php?id=<?= $teacher['id'] ?>" 
                                               class="btn btn-sm btn-edit">
                                                <i class="bi bi-pencil"></i> ویرایش
                                            </a>
                                            <a href="delete_teacher.php?id=<?= $teacher['id'] ?>" 
                                               class="btn btn-sm btn-delete"
                                               onclick="return confirm('آیا از حذف این استاد اطمینان دارید؟')">
                                                <i class="bi bi-trash"></i> حذف
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
