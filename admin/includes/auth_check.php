<?php
// بررسی احراز هویت admin
function checkAdminAuth() {
    // بررسی وجود session
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header('Location: admin_login.php');
        exit();
    }
    
    // بررسی نقش admin
    if ($_SESSION['role'] !== 'admin') {
        header('Location: admin_login.php');
        exit();
    }
}

// اجرای بررسی احراز هویت
checkAdminAuth();
?> 