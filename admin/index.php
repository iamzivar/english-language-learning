<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

// Handle actions
$allowed_actions = ['delete', 'approve'];
$allowed_entities = ['posts', 'comments', 'instructors', 'users', 'ratings', 'contact_messages'];

if (isset($_GET['action']) && isset($_GET['entity']) && isset($_GET['id'])) {
    if (in_array($_GET['action'], $allowed_actions) && in_array($_GET['entity'], $allowed_entities)) {
        $id = (int)$_GET['id'];
        
        if ($_GET['action'] == 'delete') {
            $stmt = $conn->prepare("DELETE FROM ".$_GET['entity']." WHERE id = ?");
            $stmt->execute([$id]);
        }
        elseif ($_GET['action'] == 'approve' && $_GET['entity'] == 'comments') {
            $stmt = $conn->prepare("UPDATE comments SET status = 1 WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
}

// Get data
$instructors = $conn->query("SELECT * FROM instructors ORDER BY created_at DESC LIMIT 5")->fetchAll();
$posts = $conn->query("SELECT p.*, i.name as instructor_name FROM posts p LEFT JOIN instructors i ON p.instructor_id = i.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
$comments = $conn->query("SELECT c.*, u.username, p.title as post_title FROM comments c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN posts p ON c.post_id = p.id ORDER BY c.created_at DESC LIMIT 5")->fetchAll();
$ratings = $conn->query("SELECT r.*, u.username, i.name as instructor_name FROM ratings r LEFT JOIN users u ON r.user_id = u.id LEFT JOIN instructors i ON r.instructor_id = i.id ORDER BY r.created_at DESC LIMIT 5")->fetchAll();
$contact_messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>داشبورد مدیریت</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <!-- آخرین اساتید -->
            <section class="data-section">
                <h2 class="section-title teachers-title">آخرین اساتید</h2>
                <div class="table-responsive">
                    <table class="custom-table teachers-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>نام</th>
                                <th>بیوگرافی</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($instructors as $instructor): ?>
                            <tr>
                                <td><?= $instructor['id'] ?></td>
                                <td><?= htmlspecialchars($instructor['name']) ?></td>
                                <td><?= substr(htmlspecialchars($instructor['bio']), 0, 50) ?>...</td>
                                <td class="actions">
                                    <a href="edit_teacher.php?id=<?= $instructor['id'] ?>" class="btn btn-sm btn-edit">ویرایش</a>
                                    <a href="delete_teacher.php?id=<?= $instructor['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="admin_teachers.php" class="btn btn-view-all">مشاهده همه اساتید</a>
                    <a href="create_teacher.php" class="btn btn-add-new">افزودن استاد جدید</a>
                </div>
            </section>

            <!-- آخرین پست‌ها -->
            <section class="data-section">
                <h2 class="section-title posts-title">آخرین پست‌ها</h2>
                <div class="table-responsive">
                    <table class="custom-table posts-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>عنوان</th>
                                <th>استاد</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= $post['id'] ?></td>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['instructor_name'] ?? 'بدون استاد') ?></td>
                                <td class="actions">
                                    <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-edit">ویرایش</a>
                                    <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="admin_posts.php" class="btn btn-view-all">مشاهده همه پست‌ها</a>
                    <a href="create_post.php" class="btn btn-add-new">افزودن پست جدید</a>
                </div>
            </section>

            <!-- آخرین کاربران -->
            <section class="data-section">
                <h2 class="section-title users-title">آخرین کاربران</h2>
                <div class="table-responsive">
                    <table class="custom-table users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>نام کاربری</th>
                                <th>ایمیل</th>
                                <th>نقش</th>
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
                                <td class="actions">
                                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-edit">ویرایش</a>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="admin_users.php" class="btn btn-view-all">مشاهده همه کاربران</a>
                    <a href="create_user.php" class="btn btn-add-new">افزودن کاربر جدید</a>
                </div>
            </section>

            <!-- آخرین کامنت‌ها -->
            <section class="data-section">
                <h2 class="section-title comments-title">آخرین کامنت‌ها</h2>
                <div class="table-responsive">
                    <table class="custom-table comments-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>کاربر</th>
                                <th>پست</th>
                                <th>متن</th>
                                <th>وضعیت</th>
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
                                <td class="actions">
                                    <?php if ($comment['status'] == 0): ?>
                                        <a href="?action=approve&entity=comments&id=<?= $comment['id'] ?>" class="btn btn-sm btn-approve">تأیید</a>
                                    <?php endif; ?>
                                    <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="admin_comments.php" class="btn btn-view-all">مشاهده همه کامنت‌ها</a>
                </div>
            </section>

            <!-- آخرین امتیازات -->
            <section class="data-section">
                <h2 class="section-title ratings-title">آخرین امتیازات</h2>
                <div class="table-responsive">
                    <table class="custom-table ratings-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>کاربر</th>
                                <th>استاد</th>
                                <th>امتیاز</th>
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
                                <td class="actions">
                                    <a href="delete_rating.php?id=<?= $rating['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="admin_ratings.php" class="btn btn-view-all">مشاهده همه امتیازات</a>
                </div>
            </section>

            <!-- آخرین پیام‌های تماس -->
            <section class="data-section">
                <h2 class="section-title messages-title">آخرین پیام‌های تماس</h2>
                <div class="table-responsive">
                    <table class="custom-table messages-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>نام</th>
                                <th>ایمیل</th>
                                <th>پیام</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contact_messages as $message): ?>
                            <tr>
                                <td><?= $message['id'] ?></td>
                                <td><?= htmlspecialchars($message['name']) ?></td>
                                <td><?= htmlspecialchars($message['email']) ?></td>
                                <td><?= substr(htmlspecialchars($message['message']), 0, 50) ?>...</td>
                                <td class="actions">
                                    <a href="view.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-view">مشاهده</a>
                                    <a href="delete_message.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-delete">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-actions">
                    <a href="contact_messages.php" class="btn btn-view-all">مشاهده همه پیام‌ها</a>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>