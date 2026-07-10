<?php
session_start();
include '../includes/database.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();

    if (!$post) {
        die("پست یافت نشد.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $image_url = $post['image_url']; // نگه داشتن تصویر قبلی
        
        // پردازش آپلود تصویر جدید
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['image']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $upload_dir = '../images/posts/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_name = 'post_' . time() . '_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // حذف تصویر قبلی اگر وجود داشته باشد
                    if ($post['image_url'] && file_exists('../' . $post['image_url'])) {
                        unlink('../' . $post['image_url']);
                    }
                    $image_url = 'images/posts/' . $file_name;
                } else {
                    $error = "خطا در آپلود تصویر";
                }
            } else {
                $error = "نوع فایل مجاز نیست. فقط تصاویر JPEG، PNG، GIF و WebP مجاز هستند.";
            }
        }
        
        if (!isset($error)) {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image_url = ? WHERE id = ?");
            if ($stmt->execute([$title, $content, $image_url, $postId])) {
                $_SESSION['success_message'] = "پست با موفقیت ویرایش شد!";
                header('Location: admin_posts.php');
                exit();
            }
        }
    } catch (PDOException $e) {
        $error = "خطا در ویرایش پست: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش پست</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ویرایش پست</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ویرایش پست</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-file-text"></i> عنوان:
                                </label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i> محتوا:
                                </label>
                                <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image"></i> تصویر پست:
                                </label>
                                <?php if (!empty($post['image_url'])): ?>
                                    <div class="mb-2">
                                        <img src="../<?= htmlspecialchars($post['image_url']) ?>" width="150" height="100" class="img-thumbnail">
                                        <small class="form-text text-muted">تصویر فعلی</small>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="form-text text-muted">برای تغییر تصویر، فایل جدید انتخاب کنید</small>
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-edit">
                                    <i class="bi bi-check-circle"></i> ذخیره تغییرات
                                </button>
                                <a href="admin_posts.php" class="btn btn-view-all">
                                    <i class="bi bi-arrow-left"></i> انصراف
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>