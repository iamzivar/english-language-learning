<?php
include 'layout/header.php';
include 'includes/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message_text = $_POST['message'];

    // ذخیره اطلاعات فرم تماس در دیتابیس
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt->execute([$name, $email, $message_text])) {
        $message = "پیام شما با موفقیت ارسال شد!";
    } else {
        $message = "خطا در ارسال پیام! لطفا دوباره تلاش کنید.";
    }
}
?>

<div class="container app-container">
    <div class="section-header">
        <h2>تماس با ما</h2>
        <p>برای ارتباط با ما از فرم زیر استفاده کنید</p>
    </div>
    
    <div class="form-container surface" style="max-width:720px;margin:0 auto;padding:1.25rem;">
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, 'موفقیت') !== false ? 'alert-success' : 'alert-error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">نام شما:</label>
                <input type="text" id="name" name="name" class="input" required>
            </div>

            <div class="form-group">
                <label for="email">ایمیل شما:</label>
                <input type="email" id="email" name="email" class="input" required>
            </div>

            <div class="form-group">
                <label for="message">پیام شما:</label>
                <textarea id="message" name="message" class="textarea" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">ارسال پیام</button>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>