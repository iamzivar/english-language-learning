<?php
session_start();
include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $image = $_FILES['image']['name'] ?? null;

    try {
        $stmt = $conn->prepare("INSERT INTO instructors (name, bio, image_url) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $bio, $image])) {
            // آپلود تصویر
            if ($image) {
                move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/teachers/" . $image);
            }
            $_SESSION['success_message'] = "استاد با موفقیت اضافه شد!";
            header('Location: admin_teachers.php');
            exit();
        }
    } catch (PDOException $e) {
        $error = "خطا در افزودن استاد: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>افزودن استاد جدید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>افزودن استاد جدید</h1>
    </div>

    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="main-content">
            <section class="data-section">
                <h2 class="section-title">فرم افزودن استاد</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i> نام استاد:
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">
                                    <i class="bi bi-file-text"></i> بیوگرافی:
                                </label>
                                <textarea class="form-control" id="bio" name="bio" rows="5" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image"></i> تصویر استاد:
                                </label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <div class="section-actions">
                                <button type="submit" class="btn btn-add-new">
                                    <i class="bi bi-check-circle"></i> ذخیره
                                </button>
                                <a href="admin_teachers.php" class="btn btn-view-all">
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