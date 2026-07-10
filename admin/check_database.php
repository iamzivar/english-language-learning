<?php
include '../includes/database.php';

echo "<h2>بررسی دیتابیس</h2>";

try {
    // بررسی وجود جدول users
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    $users_table_exists = $stmt->rowCount() > 0;
    
    if ($users_table_exists) {
        echo "✅ جدول 'users' وجود دارد<br>";
        
        // بررسی وجود admin
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
        $stmt->execute();
        $admin_count = $stmt->fetch()['count'];
        
        echo "👥 تعداد admin ها: $admin_count<br>";
        
    } else {
        echo "❌ جدول 'users' وجود ندارد<br>";
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
        echo "✅ جدول 'users' ایجاد شد<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ خطا: " . $e->getMessage();
}

echo "<br><a href='create_default_admin.php'>ایجاد Admin</a>";
echo "<br><a href='admin_login.php'>صفحه لاگین</a>";
?> 