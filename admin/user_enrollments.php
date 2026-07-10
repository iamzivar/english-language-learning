<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($userId <= 0) {
    header('Location: admin_enrollments_report.php');
    exit;
}

// Fetch user info
$stmtUser = $conn->prepare("SELECT id, username, email FROM users WHERE id=?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: admin_enrollments_report.php');
    exit;
}

// Fetch detailed enrollments
$stmt = $conn->prepare("SELECT c.id, c.title, c.category, c.level, c.price, c.image_url, e.enrolled_at, i.name AS instructor_name
                        FROM enrollments e
                        JOIN courses c ON c.id = e.course_id
                        LEFT JOIN instructors i ON i.id = c.instructor_id
                        WHERE e.user_id = ? AND e.status='active'
                        ORDER BY e.enrolled_at DESC");
$stmt->execute([$userId]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دوره‌های کاربر - <?= htmlspecialchars($user['username']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>دوره‌های کاربر</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">کاربر: <?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['email']) ?>)</h2>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th># دوره</th>
                                <th>تصویر</th>
                                <th>عنوان دوره</th>
                                <th>استاد</th>
                                <th>دسته‌بندی</th>
                                <th>سطح</th>
                                <th>قیمت</th>
                                <th>تاریخ ثبت‌نام</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($courses) === 0): ?>
                            <tr>
                                <td colspan="7">این کاربر در هیچ دوره‌ای ثبت‌نام فعال ندارد.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($courses as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td>
                                        <?php
                                        require_once __DIR__ . '/../includes/media.php';
                                        $img = resolveCourseImage($c['title'] ?? null, $c['category'] ?? null, $c['image_url'] ?? null, 'assets/images/grammar.jpg');
                                        // Build absolute URL from project root to avoid relative path issues
                                        $projectRoot = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/'); // e.g. /english_language_learning
                                        $imgUrl = $projectRoot . '/' . ltrim($img, '/');
                                        // Attempt PNG/JPG fallback swap if not found
                                        $fsPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $imgUrl;
                                        if (!is_file($fsPath)) {
                                            $altExt = preg_match('/\.png$/i', $img) ? '.jpg' : '.png';
                                            $imgAlt = preg_replace('/\.(png|jpg|jpeg)$/i', $altExt, $img);
                                            $imgAltUrl = $projectRoot . '/' . ltrim($imgAlt, '/');
                                            if (is_file(rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $imgAltUrl)) {
                                                $imgUrl = $imgAltUrl;
                                            }
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="" style="width:64px;height:40px;object-fit:cover;border-radius:6px;">
                                    </td>
                                    <td><?= htmlspecialchars($c['title']) ?></td>
                                    <td><?= htmlspecialchars($c['instructor_name'] ?? '—') ?></td>
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

                <div class="mt-3">
                    <a href="admin_enrollments_report.php" class="btn btn-secondary">بازگشت به گزارش</a>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


