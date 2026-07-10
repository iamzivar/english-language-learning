<?php
include '../includes/database.php';

echo "<!DOCTYPE html>
<html lang='fa' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>راه‌اندازی پنل مدیریت</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
    <style>
        body { background: linear-gradient(135deg, #D5E5D5, #C7D9DD); padding: 2rem; }
        .setup-card { background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .step { margin-bottom: 1.5rem; padding: 1rem; border-radius: 10px; border-right: 4px solid #27ae60; }
        .step.success { background: #d4edda; border-color: #28a745; }
        .step.error { background: #f8d7da; border-color: #dc3545; }
        .step.info { background: #d1ecf1; border-color: #17a2b8; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='row justify-content-center'>
            <div class='col-md-8'>
                <div class='setup-card'>
                    <h1 class='text-center mb-4'>
                        <i class='bi bi-gear-fill text-primary'></i>
                        راه‌اندازی پنل مدیریت
                    </h1>";

try {
    // مرحله 1: بررسی اتصال دیتابیس
    echo "<div class='step success'>
            <h5><i class='bi bi-check-circle text-success'></i> مرحله 1: اتصال دیتابیس</h5>
            <p>✅ اتصال به دیتابیس برقرار شد</p>
          </div>";
    
    // مرحله 2: ایجاد جدول users
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    $users_table_exists = $stmt->rowCount() > 0;
    
    if (!$users_table_exists) {
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->exec($sql);
        echo "<div class='step success'>
                <h5><i class='bi bi-check-circle text-success'></i> مرحله 2: ایجاد جدول users</h5>
                <p>✅ جدول users ایجاد شد</p>
              </div>";
    } else {
        echo "<div class='step info'>
                <h5><i class='bi bi-info-circle text-info'></i> مرحله 2: بررسی جدول users</h5>
                <p>ℹ️ جدول users قبلاً وجود دارد</p>
              </div>";
    }
    
    // مرحله 3: ایجاد admin پیش‌فرض
    $admin_username = 'admin';
    $admin_email = 'admin@example.com';
    $admin_password = 'admin123';
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$admin_email, $admin_username]);
    $existing_admin = $stmt->fetch();
    
    if (!$existing_admin) {
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'admin', NOW())");
        $stmt->execute([$admin_username, $admin_email, $hashed_password]);
        
        echo "<div class='step success'>
                <h5><i class='bi bi-check-circle text-success'></i> مرحله 3: ایجاد admin پیش‌فرض</h5>
                <p>✅ Admin با موفقیت ایجاد شد</p>
                <div class='alert alert-info'>
                    <strong>اطلاعات ورود:</strong><br>
                    📧 ایمیل: $admin_email<br>
                    👤 نام کاربری: $admin_username<br>
                    🔑 رمز عبور: $admin_password
                </div>
              </div>";
    } else {
        echo "<div class='step info'>
                <h5><i class='bi bi-info-circle text-info'></i> مرحله 3: بررسی admin</h5>
                <p>ℹ️ Admin قبلاً وجود دارد</p>
                <div class='alert alert-info'>
                    <strong>اطلاعات ورود:</strong><br>
                    📧 ایمیل: $admin_email<br>
                    👤 نام کاربری: $admin_username<br>
                    🔑 رمز عبور: $admin_password
                </div>
              </div>";
    }
    
    // مرحله 4: بررسی سایر جداول
    $required_tables = [
        'instructors' => 'اساتید',
        'posts' => 'پست‌ها',
        'comments' => 'کامنت‌ها',
        'ratings' => 'امتیازات',
        'contact_messages' => 'پیام‌های تماس',
        'courses' => 'دوره‌ها',
        'enrollments' => 'ثبت‌نام‌ها'
    ];
    
    echo "<div class='step info'>
            <h5><i class='bi bi-info-circle text-info'></i> مرحله 4: بررسی جداول مورد نیاز</h5>";
    
    foreach ($required_tables as $table => $title) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            echo "<p>✅ جدول $title ($table) وجود دارد</p>";
        } else {
            echo "<p>⚠️ جدول $title ($table) وجود ندارد</p>";
        }
    }
    
    echo "</div>";
    
    // مرحله 5: تکمیل
    echo "<div class='step success'>
            <h5><i class='bi bi-check-circle text-success'></i> مرحله 5: تکمیل راه‌اندازی</h5>
            <p>✅ پنل مدیریت آماده استفاده است</p>
          </div>";
    
    echo "<div class='text-center mt-4'>
            <a href='admin_login.php' class='btn btn-primary btn-lg'>
                <i class='bi bi-box-arrow-in-right'></i> ورود به پنل مدیریت
            </a>
            <br><br>
            <small class='text-muted'>پس از ورود، حتماً رمز عبور پیش‌فرض را تغییر دهید</small>
          </div>";
    
} catch (PDOException $e) {
    echo "<div class='step error'>
            <h5><i class='bi bi-exclamation-triangle text-danger'></i> خطا</h5>
            <p>❌ خطا در راه‌اندازی: " . $e->getMessage() . "</p>
          </div>";
}

echo "</div>
        </div>
    </div>
</body>
</html>";
?> 