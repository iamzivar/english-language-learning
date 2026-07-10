<?php
include '../includes/database.php';

// اطلاعات admin پیش‌فرض
$admin_username = 'admin';
$admin_email = 'admin@example.com';
$admin_password = 'admin123'; // رمز عبور پیش‌فرض
$admin_role = 'admin';

try {
    // بررسی وجود جدول users
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    $users_table_exists = $stmt->rowCount() > 0;
    
    if (!$users_table_exists) {
        echo "🔧 ایجاد جدول users...<br>";
        
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->exec($sql);
        echo "✅ جدول 'users' ایجاد شد<br><br>";
    }
    
    // بررسی وجود admin
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$admin_email, $admin_username]);
    $existing_admin = $stmt->fetch();

    if ($existing_admin) {
        echo "✅ Admin قبلاً وجود دارد!<br>";
        echo "📧 ایمیل: $admin_email<br>";
        echo "👤 نام کاربری: $admin_username<br>";
        echo "🔑 رمز عبور: $admin_password<br>";
    } else {
        // ایجاد admin جدید
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$admin_username, $admin_email, $hashed_password, $admin_role]);
        
        echo "✅ Admin با موفقیت ایجاد شد!<br>";
        echo "📧 ایمیل: $admin_email<br>";
        echo "👤 نام کاربری: $admin_username<br>";
        echo "🔑 رمز عبور: $admin_password<br>";
    }
    
    echo "<br>🔗 <a href='admin_login.php'>ورود به پنل مدیریت</a>";
    
} catch (PDOException $e) {
    echo "❌ خطا در ایجاد admin: " . $e->getMessage();
}
?> 