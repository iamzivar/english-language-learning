<?php
session_start();

// حذف تمام متغیرهای session
$_SESSION = array();

// حذف cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// نابودی session
session_destroy();

// هدایت به صفحه اصلی
header("Location: index.php");
exit();
?>
