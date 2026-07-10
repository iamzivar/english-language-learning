<?php
session_start();
include '../includes/database.php';
include 'includes/auth_check.php';

// Config
$minEnrollmentsPerUser = 1;
$maxEnrollmentsPerUser = 3;

try {
    // Fetch non-admin users
    $stmtUsers = $conn->query("SELECT id FROM users WHERE role='user' ORDER BY id");
    $users = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

    // Fetch active courses
    $stmtCourses = $conn->query("SELECT id FROM courses WHERE status='active'");
    $courses = $stmtCourses->fetchAll(PDO::FETCH_COLUMN);

    if (empty($users) || empty($courses)) {
        echo 'No users or courses to seed';
        exit;
    }

    $totalUsers = count($users);
    $targetUsers = (int)ceil($totalUsers / 2); // at least half

    // Randomly pick users to enroll (at least half)
    shuffle($users);
    $selectedUsers = array_slice($users, 0, $targetUsers);

    // Prepare statements
    $stmtInsert = $conn->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'active')");
    $stmtExists = $conn->prepare("SELECT id FROM enrollments WHERE user_id=? AND course_id=?");

    $enrolledCount = 0;
    foreach ($selectedUsers as $userId) {
        $numEnrolls = rand($minEnrollmentsPerUser, min($maxEnrollmentsPerUser, max(1, count($courses))));

        // Pick random distinct courses
        $shuffledCourses = $courses;
        shuffle($shuffledCourses);
        $chosen = array_slice($shuffledCourses, 0, $numEnrolls);

        foreach ($chosen as $courseId) {
            // Skip if exists
            $stmtExists->execute([$userId, $courseId]);
            if ($stmtExists->fetch()) {
                continue;
            }
            try {
                $stmtInsert->execute([$userId, $courseId]);
                $enrolledCount++;
            } catch (PDOException $e) {
                // ignore duplicates or constraint issues per row
            }
        }
    }

    echo "Seed complete. Enrollments created: " . $enrolledCount;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>


