<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

// Fetch aggregated user enrollment counts
$queryCounts = "
    SELECT u.id AS user_id,
           u.username,
           u.email,
           COUNT(e.id) AS total_enrollments
    FROM users u
    LEFT JOIN enrollments e
      ON e.user_id = u.id AND e.status = 'active'
    GROUP BY u.id, u.username, u.email
    ORDER BY total_enrollments DESC, u.created_at DESC
";
$stmtCounts = $conn->query($queryCounts);
$userCounts = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);

// Optional: fetch detailed enrollments for a selected user
$selectedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$userDetails = [];
if ($selectedUserId > 0) {
    $stmtDetails = $conn->prepare("SELECT c.id, c.title, c.category, c.level, c.price, e.enrolled_at
                                   FROM enrollments e
                                   JOIN courses c ON c.id = e.course_id
                                   WHERE e.user_id = ? AND e.status='active'");
    $stmtDetails->execute([$selectedUserId]);
    $userDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش ثبت‌نام کاربران</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>گزارش ثبت‌نام کاربران</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">تعداد دوره‌های ثبت‌نام‌شده به تفکیک کاربر</h2>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام کاربری</th>
                                <th>ایمیل</th>
                                <th>تعداد دوره‌ها</th>
                                <th>جزئیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userCounts as $row): ?>
                            <tr>
                                <td><?= $row['user_id'] ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><span class="badge bg-primary"><?= (int)$row['total_enrollments'] ?></span></td>
                                <td>
                                    <a href="user_enrollments.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-info">
                                        مشاهده دوره‌ها
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($selectedUserId > 0): ?>
            <section class="data-section mt-4">
                <h2 class="section-title">دوره‌های کاربر #<?= $selectedUserId ?></h2>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th># دوره</th>
                                <th>عنوان دوره</th>
                                <th>دسته‌بندی</th>
                                <th>سطح</th>
                                <th>قیمت</th>
                                <th>تاریخ ثبت‌نام</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($userDetails) === 0): ?>
                            <tr>
                                <td colspan="6">ثبت‌نام فعالی یافت نشد.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($userDetails as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td><?= htmlspecialchars($c['title']) ?></td>
                                    <td><?= htmlspecialchars($c['category']) ?></td>
                                    <td><?= htmlspecialchars($c['level']) ?></td>
                                    <td><?= htmlspecialchars($c['price']) ?></td>
                                    <td><?= date('Y/m/d H:i', strtotime($c['enrolled_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 </body>
</html>


