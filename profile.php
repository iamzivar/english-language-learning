<?php
session_start();
include 'includes/database.php';
include_once 'includes/media.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$current_user = $_SESSION['user'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate current password
    if (!empty($current_password)) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = "رمز عبور فعلی اشتباه است";
        }
    }
    
    // Check if username is already taken
    if ($username !== $current_user['username']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = "نام کاربری قبلاً استفاده شده است";
        }
    }
    
    // Check if email is already taken
    if ($email !== $current_user['email']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = "ایمیل قبلاً استفاده شده است";
        }
    }
    
    // Validate new password
    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $errors[] = "رمز عبور جدید باید حداقل 6 کاراکتر باشد";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "رمز عبور جدید و تکرار آن مطابقت ندارند";
        }
    }
    
    if (empty($errors)) {
        // Update user information
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$username, $email, $hashed_password, $user_id]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
        }
        
        // Update session
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $current_user = $_SESSION['user'];
        
        $success_message = "اطلاعات پروفایل با موفقیت بروزرسانی شد";
    }
}

// Get user's comments
$stmt = $conn->prepare("
    SELECT c.*, p.title as post_title, p.id as post_id
    FROM comments c
    JOIN posts p ON c.post_id = p.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
    LIMIT 10
");
$stmt->execute([$user_id]);
$user_comments = $stmt->fetchAll();

// Get user's ratings
$stmt = $conn->prepare("
    SELECT r.*, i.name as instructor_name, i.id as instructor_id
    FROM ratings r
    JOIN instructors i ON r.instructor_id = i.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
    LIMIT 10
");
$stmt->execute([$user_id]);
$user_ratings = $stmt->fetchAll();

$userAvatar = resolveImagePath($current_user['avatar'] ?? null, 'assets/images/default-avatar.jpg');
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربر - آکادمی آموزش زبان انگلیسی</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        
        .profile-sidebar {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        
        .profile-main {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
            display: block;
            border: 4px solid #f0f0f0;
        }
        
        .profile-info h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .profile-tabs {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 2rem;
        }
        
        .tab-button {
            padding: 1rem 2rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s ease;
        }
        
        .activity-item:hover {
            background-color: #f8f9fa;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .activity-title {
            font-weight: 600;
            color: #333;
        }
        
        .activity-date {
            color: #999;
            font-size: 0.9rem;
        }
        
        .activity-content {
            color: #666;
            line-height: 1.6;
        }
        
        .no-activity {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'layout/header.php'; ?>
    
    <div class="container">
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <img src="<?= htmlspecialchars($userAvatar) ?>" 
                     alt="پروفایل کاربر" class="profile-avatar">
                
                <div class="profile-info">
                    <h2><?= htmlspecialchars($current_user['username']) ?></h2>
                    <p style="text-align: center; color: #666; margin-bottom: 1rem;">
                        <?= htmlspecialchars($current_user['email']) ?>
                    </p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= count($user_comments) ?></div>
                            <div class="stat-label">نظر</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count($user_ratings) ?></div>
                            <div class="stat-label">امتیاز</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="profile-main">
                <div class="profile-tabs">
                    <button class="tab-button active" onclick="showTab('profile')">
                        <i class="fas fa-user"></i> اطلاعات پروفایل
                    </button>
                    <button class="tab-button" onclick="showTab('comments')">
                        <i class="fas fa-comments"></i> نظرات من
                    </button>
                    <button class="tab-button" onclick="showTab('ratings')">
                        <i class="fas fa-star"></i> امتیازات من
                    </button>
                </div>
                
                <!-- Profile Tab -->
                <div id="profile" class="tab-content active">
                    <h3>ویرایش اطلاعات پروفایل</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                        <?php foreach ($errors as $error): ?>
                            <div class="alert alert-error"><?= $error ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <form method="POST" class="form-container">
                        <div class="form-group">
                            <label for="username">نام کاربری:</label>
                            <input type="text" id="username" name="username" 
                                   value="<?= htmlspecialchars($current_user['username']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">ایمیل:</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($current_user['email']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="current_password">رمز عبور فعلی:</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">رمز عبور جدید:</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">تکرار رمز عبور جدید:</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> بروزرسانی پروفایل
                        </button>
                    </form>
                </div>
                
                <!-- Comments Tab -->
                <div id="comments" class="tab-content">
                    <h3>نظرات من</h3>
                    
                    <?php if (empty($user_comments)): ?>
                        <div class="no-activity">
                            <i class="fas fa-comments" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <h4>هنوز نظری ننوشته‌اید</h4>
                            <p>برای مشاهده نظرات خود، ابتدا در مقالات نظر بگذارید</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($user_comments as $comment): ?>
                            <div class="activity-item">
                                <div class="activity-header">
                                    <div class="activity-title">
                                        <a href="post.php?id=<?= $comment['post_id'] ?>" style="color: inherit; text-decoration: none;">
                                            <?= htmlspecialchars($comment['post_title']) ?>
                                        </a>
                                    </div>
                                    <div class="activity-date">
                                        <?= date('Y/m/d H:i', strtotime($comment['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="activity-content">
                                    <?= htmlspecialchars($comment['content']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Ratings Tab -->
                <div id="ratings" class="tab-content">
                    <h3>امتیازات من</h3>
                    
                    <?php if (empty($user_ratings)): ?>
                        <div class="no-activity">
                            <i class="fas fa-star" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <h4>هنوز امتیازی نداده‌اید</h4>
                            <p>برای مشاهده امتیازات خود، ابتدا به اساتید امتیاز دهید</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($user_ratings as $rating): ?>
                            <div class="activity-item">
                                <div class="activity-header">
                                    <div class="activity-title">
                                        <a href="teacher-profile.php?id=<?= $rating['instructor_id'] ?>" style="color: inherit; text-decoration: none;">
                                            <?= htmlspecialchars($rating['instructor_name']) ?>
                                        </a>
                                    </div>
                                    <div class="activity-date">
                                        <?= date('Y/m/d H:i', strtotime($rating['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="activity-content">
                                    <div style="margin-bottom: 0.5rem;">
                                        <span style="color: #ffd700;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $rating['rating'] ? '' : 'far' ?>"></i>
                                            <?php endfor; ?>
                                        </span>
                                        <span style="color: #666; margin-right: 0.5rem;">
                                            امتیاز: <?= $rating['rating'] ?>/5
                                        </span>
                                    </div>
                                    <?php if ($rating['comment']): ?>
                                        <p><?= htmlspecialchars($rating['comment']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'layout/footer.php'; ?>
    
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => button.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
