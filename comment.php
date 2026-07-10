<?php
include 'layout/header.php';
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = 1;  // در اینجا باید کاربر وارد شده را بگیرید
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $post_id, $content])) {
        echo "<div class='alert alert-success'>کامنت با موفقیت ارسال شد!</div>";
        header("Location: post.php?id=" . $post_id);
        exit();
    } else {
        echo "<div class='alert alert-error'>خطا در ارسال کامنت!</div>";
    }
}
?>

<div class="main-container">
    <div class="comment-form-page">
        <h1>ارسال نظر</h1>
        <p>نظر خود را درباره این مقاله بنویسید.</p>
        
        <form action="comment.php" method="POST" class="comment-form">
            <input type="hidden" name="post_id" value="<?= $_GET['post_id'] ?? '' ?>">
            <textarea name="content" placeholder="نظر خود را بنویسید..." required></textarea>
            <button type="submit" class="btn btn-primary">ارسال نظر</button>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>