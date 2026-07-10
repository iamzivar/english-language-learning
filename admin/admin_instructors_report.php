<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

// Aggregated metrics per instructor
$sql = "
SELECT * FROM (
    SELECT i.id AS instructor_id,
           i.name AS instructor_name,
           COUNT(DISTINCT c.id) AS courses_count,
           COUNT(DISTINCT e.user_id) AS students_count,
           ROUND(AVG(r.rating), 2) AS avg_rating
    FROM instructors i
    LEFT JOIN courses c ON c.instructor_id = i.id
    LEFT JOIN enrollments e ON e.course_id = c.id AND e.status='active'
    LEFT JOIN ratings r ON r.instructor_id = i.id
    GROUP BY i.id, i.name
) t
ORDER BY t.courses_count DESC,
         t.students_count DESC,
         (t.avg_rating IS NULL) ASC,
         t.avg_rating DESC
";
$rows = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش اساتید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>گزارش اساتید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">نمای کلی اساتید</h2>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام استاد</th>
                                <th>تعداد دوره‌ها</th>
                                <th>تعداد دانشجویان</th>
                                <th>میانگین امتیاز</th>
                                <th>جزئیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= $r['instructor_id'] ?></td>
                                <td><?= htmlspecialchars($r['instructor_name']) ?></td>
                                <td><span class="badge bg-primary"><?= (int)$r['courses_count'] ?></span></td>
                                <td><span class="badge bg-success"><?= (int)$r['students_count'] ?></span></td>
                                <td>
                                    <?php if ($r['avg_rating'] !== null): ?>
                                        <span class="badge bg-warning text-dark"><?= number_format($r['avg_rating'], 2) ?></span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="instructor_overview.php?instructor_id=<?= $r['instructor_id'] ?>" class="btn btn-sm btn-info">مشاهده</a>
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


