<?php
session_start();
include '../includes/database.php';

$stmt = $conn->prepare("SELECT p.*, i.name AS instructor_name 
                      FROM posts p
                      LEFT JOIN instructors i ON p.instructor_id = i.id
                      ORDER BY p.created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت پست‌ها</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت پست‌ها</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست پست‌ها</h2>
                
                <div class="section-actions">
                    <a href="create_post.php" class="btn btn-add-new">
                        <i class="bi bi-plus-circle"></i> ایجاد پست جدید
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
                                <th>استاد مربوطه</th>
                                <th>تصویر</th>
                                <th>تاریخ ایجاد</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= $post['id'] ?></td>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['instructor_name'] ?? 'بدون استاد') ?></td>
                                <td>
                                    <?php if (!empty($post['image_url'])): ?>
                                        <img src="../<?= htmlspecialchars($post['image_url']) ?>" width="50" height="50" class="img-thumbnail">
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y/m/d H:i', strtotime($post['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </a>
                                    <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('آیا مطمئن هستید؟')">
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