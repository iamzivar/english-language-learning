<?php
session_start();
include '../includes/database.php';

// بررسی وجود جدول contact_messages
$tableExists = $conn->query("SHOW TABLES LIKE 'contact_messages'")->rowCount() > 0;
if (!$tableExists) {
    die("جدول پیام‌های تماس در دیتابیس وجود ندارد");
}

// پردازش عملیات
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action == 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success_message'] = 'پیام با موفقیت حذف شد';
    } else {
        $_SESSION['error_message'] = 'خطا در حذف پیام';
    }
    header('Location: contact_messages.php');
    exit();
}

// دریافت پیام‌ها
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت پیام‌های تماس</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت پیام‌های تماس</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست پیام‌های تماس</h2>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error_message'] ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام</th>
                                <th>ایمیل</th>
                                <th>متن پیام</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($messages)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="bi bi-info-circle"></i> هیچ پیامی یافت نشد.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?= $message['id'] ?></td>
                                    <td><?= htmlspecialchars($message['name']) ?></td>
                                    <td><?= htmlspecialchars($message['email']) ?></td>
                                    <td><?= substr(htmlspecialchars($message['message']), 0, 50) ?>...</td>
                                    <td><?= date('Y/m/d H:i', strtotime($message['created_at'])) ?></td>
                                    <td class="actions">
                                        <a href="view.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-view">
                                            <i class="bi bi-eye"></i> مشاهده
                                        </a>
                                        <a href="contact_messages.php?action=delete&id=<?= $message['id'] ?>" 
                                           class="btn btn-sm btn-delete"
                                           onclick="return confirm('آیا از حذف این پیام اطمینان دارید؟')">
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