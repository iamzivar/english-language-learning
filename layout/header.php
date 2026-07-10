<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'name' => 'EL_SESSID',
        'cookie_lifetime' => 86400,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'use_strict_mode' => true
    ]);
}

$current_user = $_SESSION['user'] ?? null;
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آکادمی آموزش زبان انگلیسی</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <script src="assets/js/main.js" defer></script>
</head>
<body>
    <a href="#main" class="skip-link">پرش به محتوای اصلی</a>
    <nav class="navbar pro-navbar">
        <div class="nav-container app-container pro-wrap">
            <!-- Logo Section -->
            <div class="nav-brand">
                <a href="index.php" class="logo pro-brand">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">آکادمی زبان</span>
                        <span class="logo-subtitle">انگلیسی</span>
                    </div>
                </a>
            </div>
            
            <!-- Navigation Links -->
            <div class="nav-links pro-nav">
                <a href="index.php" class="nav-link pro-link">
                    <i class="fas fa-home"></i>
                    <span>خانه</span>
                </a>
                <a href="teachers.php" class="nav-link pro-link">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>اساتید</span>
                </a>
                <a href="posts.php" class="nav-link pro-link">
                    <i class="fas fa-newspaper"></i>
                    <span>مقالات</span>
                </a>
                <a href="courses.php" class="nav-link pro-link">
                    <i class="fas fa-book"></i>
                    <span>دوره‌ها</span>
                </a>
                <a href="search.php" class="nav-link pro-link">
                    <i class="fas fa-search"></i>
                    <span>جستجو</span>
                </a>
            </div>
            
            <!-- Authentication Section -->
            <div class="nav-auth">
                <button class="nav-theme-toggle" id="themeToggle" aria-label="تغییر به حالت تاریک">
                    <i class="fas fa-moon"></i>
                </button>
                <?php if ($current_user): ?>
                    <div class="user-menu">
                        <a href="profile.php" class="user-profile">
                            <div class="user-avatar">
                                <img src="<?= htmlspecialchars($current_user['avatar'] ?? 'assets/images/default-avatar.jpg') ?>" alt="پروفایل کاربر">
                                <div class="online-indicator"></div>
                            </div>
                            <div class="user-info">
                                <span class="username"><?= htmlspecialchars($current_user['username']) ?></span>
                                <span class="user-status">آنلاین</span>
                            </div>
                        </a>
                        <div class="user-dropdown">
                            <a href="profile.php" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>پروفایل</span>
                            </a>
                            <a href="logout.php" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>خروج</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="login.php" class="auth-btn login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>ورود</span>
                        </a>
                        <a href="register.php" class="auth-btn register-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>ثبت‌نام</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" aria-label="منوی موبایل">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>
    
    <main class="main-container" id="main">