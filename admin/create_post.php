<?php
session_start();

// // بررسی آیا کاربر لاگین کرده و مدیر است
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $instructor_id = $_POST['instructor_id'] ?? null;
    
    // اعتبارسنجی داده‌ها
    if (empty($title) || empty($content)) {
        $error = "لطفاً عنوان و محتوا را وارد کنید";
    } else {
        try {
            $image_url = null;
            
            // پردازش آپلود تصویر
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
                        $image_url = 'images/posts/' . $file_name;
                    } else {
                        $error = "خطا در آپلود تصویر";
                    }
                } else {
                    $error = "نوع فایل مجاز نیست. فقط تصاویر JPEG، PNG، GIF و WebP مجاز هستند.";
                }
            }
            
            if (!isset($error)) {
                $stmt = $conn->prepare("INSERT INTO posts (title, content, image_url, instructor_id) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$title, $content, $image_url, $instructor_id])) {
                    $_SESSION['success_message'] = "پست با موفقیت ایجاد شد!";
                    header('Location: admin_posts.php');
                    exit();
                }
            }
        } catch (PDOException $e) {
            $error = "خطا در ایجاد پست: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایجاد پست جدید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>ایجاد پست جدید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم ایجاد پست</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-file-text"></i> عنوان پست:
                                </label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i> محتوا:
                                </label>
                                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="instructor_id" class="form-label">
                                    <i class="bi bi-person-video3"></i> استاد مربوطه:
                                </label>
                                <select class="form-select" id="instructor_id" name="instructor_id">
                                    <option value="">بدون استاد</option>
                                    <?php
                                    $instructors = $conn->query("SELECT id, name FROM instructors");
                                    foreach ($instructors as $instructor): ?>
                                        <option value="<?= $instructor['id'] ?>"><?= htmlspecialchars($instructor['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image"></i> تصویر پست:
                                </label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-add-new">
                                    <i class="bi bi-check-circle"></i> ایجاد پست
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