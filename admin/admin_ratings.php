<?php
session_start();
include '../includes/database.php';

$stmt = $conn->prepare("SELECT r.*, u.username, i.name as instructor_name 
                      FROM ratings r
                      LEFT JOIN users u ON r.user_id = u.id
                      LEFT JOIN instructors i ON r.instructor_id = i.id
                      ORDER BY r.created_at DESC");
$stmt->execute();
$ratings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت امتیازات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مدیریت امتیازات</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">لیست امتیازات</h2>

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
                                <th>استاد</th>
                                <th>امتیاز</th>
                                <th>تاریخ</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ratings as $rating): ?>
                            <tr>
                                <td><?= $rating['id'] ?></td>
                                <td><?= htmlspecialchars($rating['username']) ?></td>
                                <td><?= htmlspecialchars($rating['instructor_name']) ?></td>
                                <td>
                                    <span class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $rating['rating']): ?>
                                                ★
                                            <?php else: ?>
                                                ☆
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </span>
                                    (<?= $rating['rating'] ?>/5)
                                </td>
                                <td><?= date('Y/m/d H:i', strtotime($rating['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="delete_rating.php?id=<?= $rating['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('آیا مطمئن هستید؟')">
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