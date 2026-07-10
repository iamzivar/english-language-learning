<?php
session_start();
require_once __DIR__ . '/includes/database.php';

// Validate required fields
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

if ($user_id <= 0 || $course_id <= 0) {
    $_SESSION['error_message'] = 'درخواست نامعتبر است.';
    header('Location: courses.php');
    exit;
}

try {
    // Ensure course exists and is active
    $stmt = $conn->prepare("SELECT id FROM courses WHERE id = ? AND status = 'active'");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$course) {
        $_SESSION['error_message'] = 'دوره معتبر نیست یا غیرفعال است.';
        header('Location: courses.php');
        exit;
    }

    // Insert enrollment (idempotent)
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'active')");
    $stmt->execute([$user_id, $course_id]);

    $_SESSION['success_message'] = 'ثبت‌نام شما با موفقیت انجام شد.';
    header('Location: preview.php?course_id=' . $course_id);
    exit;
} catch (PDOException $e) {
    // Handle duplicate entry gracefully
    if ($e->errorInfo[1] == 1062) { // MySQL duplicate key
        // If already exists but cancelled, set to active again
        try {
            $stmt = $conn->prepare("UPDATE enrollments SET status='active' WHERE user_id=? AND course_id=?");
            $stmt->execute([$user_id, $course_id]);
            $_SESSION['success_message'] = 'شما قبلاً ثبت‌نام کرده بودید. وضعیت فعال شد.';
            header('Location: preview.php?course_id=' . $course_id);
            exit;
        } catch (PDOException $e2) {
            $_SESSION['error_message'] = 'خطا در بروزرسانی ثبت‌نام.';
            header('Location: enroll.php?course_id=' . $course_id);
            exit;
        }
    }

    $_SESSION['error_message'] = 'خطا در ثبت‌نام: ' . $e->getMessage();
    header('Location: enroll.php?course_id=' . $course_id);
    exit;
}
?>


