<?php
include 'includes/database.php'; // مسیر صحیح فایل دیتابیس

$message = ""; // متغیر برای نمایش پیام‌ها

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $repeat_password = trim($_POST['repeat_password']);

    // بررسی تکراری نبودن ایمیل
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $message = "این ایمیل قبلاً ثبت شده است!";
    } elseif ($password !== $repeat_password) {
        $message = "رمزهای عبور یکسان نیستند!";
    } else {
        // رمز عبور را هش کن
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ثبت اطلاعات در دیتابیس
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $message = "ثبت‌نام موفقیت‌آمیز بود!";
            // می‌توانید کاربر را به صفحه لاگین هدایت کنید
            // header("Location: login.php");
        } else {
            $message = "خطا در ثبت‌نام! لطفاً دوباره تلاش کنید.";
        }
    }
}
?>

<?php include 'layout/header.php'; ?>

<div class="container app-container">
    <div class="register-form-container surface" style="max-width:560px;margin:1.25rem auto;padding:1.25rem;">
        <h2>ثبت‌نام</h2>

        <?php if ($message): ?>
            <div class="alert <?= strpos($message, 'موفقیت') !== false ? 'alert-success' : 'alert-error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">نام کاربری:</label>
                <input type="text" id="username" name="username" class="input" placeholder="نام کاربری خود را وارد کنید" required>
            </div>
            
            <div class="form-group">
                <label for="email">ایمیل:</label>
                <input type="email" id="email" name="email" class="input" placeholder="ایمیل خود را وارد کنید" required>
            </div>
            
            <div class="form-group">
                <label for="password">رمز عبور:</label>
                <input type="password" id="password" name="password" class="input" placeholder="رمز عبور خود را وارد کنید" required>
            </div>
            
            <div class="form-group">
                <label for="repeat_password">تکرار رمز عبور:</label>
                <input type="password" id="repeat_password" name="repeat_password" class="input" placeholder="رمز عبور را تکرار کنید" required>
            </div>
            
            <button type="submit" class="btn btn-primary">ثبت‌نام</button>
        </form>
        
        <div class="form-footer">
            <p>حساب کاربری دارید؟ <a href="login.php">ورود کنید</a></p>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>