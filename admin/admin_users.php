<?php
session_start();
include '../includes/database.php';

$stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت کاربران</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت کاربران</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست کاربران</h2>
                
                <div class="section-actions">
                    <a href="create_user.php" class="btn btn-add-new">
                        <i class="bi bi-plus-circle"></i> افزودن کاربر جدید
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
                                <th>نام کاربری</th>
                                <th>ایمیل</th>
                                <th>نقش</th>
                                <th>تاریخ ثبت‌نام</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php if ($user['role'] == 'admin'): ?>
                                        <span class="badge bg-danger">مدیر</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">کاربر عادی</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y/m/d H:i', strtotime($user['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </a>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('آیا از حذف این کاربر اطمینان دارید؟')">
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