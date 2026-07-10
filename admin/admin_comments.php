<?php
session_start();
include '../includes/database.php';

$stmt = $conn->prepare("SELECT c.*, u.username, p.title as post_title 
                      FROM comments c
                      LEFT JOIN users u ON c.user_id = u.id
                      LEFT JOIN posts p ON c.post_id = p.id
                      ORDER BY c.created_at DESC");
$stmt->execute();
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت کامنت‌ها</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت کامنت‌ها</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست کامنت‌ها</h2>

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
                                <th>کاربر</th>
                                <th>پست</th>
                                <th>متن</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td><?= $comment['id'] ?></td>
                                <td><?= htmlspecialchars($comment['username']) ?></td>
                                <td><?= htmlspecialchars($comment['post_title']) ?></td>
                                <td><?= substr(htmlspecialchars($comment['content']), 0, 50) ?>...</td>
                                <td>
                                    <?php if ($comment['status'] == 1): ?>
                                        <span class="badge bg-success">تأیید شده</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">در انتظار تأیید</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y/m/d H:i', strtotime($comment['created_at'])) ?></td>
                                <td class="actions">
                                    <?php if ($comment['status'] == 0): ?>
                                        <a href="?action=approve&entity=comments&id=<?= $comment['id'] ?>" class="btn btn-sm btn-approve">
                                            <i class="bi bi-check-circle"></i> تأیید
                                        </a>
                                    <?php endif; ?>
                                    <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('آیا مطمئن هستید؟')">
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