<?php
session_start();
include '../includes/database.php';
if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit();
}

$messageId = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->execute([$messageId]);
$message = $stmt->fetch();

if (!$message) {
    die("پیام مورد نظر یافت نشد");
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاهده پیام تماس</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>مشاهده پیام تماس</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">جزئیات پیام</h2>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person"></i> نام:
                            </label>
                            <p><?= htmlspecialchars($message['name']) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> ایمیل:
                            </label>
                            <p><?= htmlspecialchars($message['email']) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar"></i> تاریخ ارسال:
                            </label>
                            <p><?= date('Y/m/d H:i', strtotime($message['created_at'])) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat"></i> پیام کامل:
                            </label>
                            <div class="border p-3 bg-light">
                                <?= nl2br(htmlspecialchars($message['message'])) ?>
                            </div>
                        </div>

                        <div class="section-actions">
                            <a href="javascript:history.back()" class="btn btn-view-all">
                                <i class="bi bi-arrow-left"></i> بازگشت
                            </a>
                            <a href="?action=delete&entity=contact_messages&id=<?= $message['id'] ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('آیا از حذف این پیام اطمینان دارید؟')">
                                <i class="bi bi-trash"></i> حذف پیام
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>