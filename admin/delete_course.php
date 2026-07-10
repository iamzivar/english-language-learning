<?php
session_start();
include '../includes/database.php';

if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
    
    try {
        // حذف دوره
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        if ($stmt->execute([$courseId])) {
            $_SESSION['success_message'] = "دوره با موفقیت حذف شد!";
        } else {
            $_SESSION['error_message'] = "خطا در حذف دوره";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "خطا در حذف دوره: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "شناسه دوره مشخص نشده است";
}

header('Location: admin_courses.php');
exit();
?>
