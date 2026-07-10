<?php
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // شروع session و ذخیره اطلاعات کاربر
        session_start();
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error_message = "ایمیل یا رمز عبور اشتباه است!";
    }
}
?>

<?php include 'layout/header.php'; ?>

<div class="container app-container">
    <div class="login-form-container surface" style="max-width:560px;margin:1.25rem auto;padding:1.25rem;">
        <h2>ورود به سیستم</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?= $error_message ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">ایمیل:</label>
                <input type="email" id="email" name="email" class="input" placeholder="ایمیل خود را وارد کنید" required>
            </div>
            
            <div class="form-group">
                <label for="password">رمز عبور:</label>
                <input type="password" id="password" name="password" class="input" placeholder="رمز عبور خود را وارد کنید" required>
            </div>
            
            <button type="submit" class="btn btn-primary">ورود</button>
        </form>
        
        <div class="form-footer">
            <p>حساب کاربری ندارید؟ <a href="register.php">ثبت‌نام کنید</a></p>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>