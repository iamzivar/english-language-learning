<?php
session_start();

// اگر کاربر قبلاً لاگین کرده و admin است، به داشبورد هدایت شود
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: index.php');
    exit();
}

include '../includes/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'لطفاً تمام فیلدها را پر کنید';
    } else {
        try {
            // بررسی وجود جدول users
            $stmt = $conn->query("SHOW TABLES LIKE 'users'");
            $users_table_exists = $stmt->rowCount() > 0;
            
            if (!$users_table_exists) {
                $error = 'جدول users وجود ندارد. لطفاً ابتدا admin ایجاد کنید.';
            } else {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // لاگین موفق
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    $success = 'ورود موفقیت‌آمیز بود! در حال هدایت...';
                    
                    // هدایت به داشبورد بعد از 2 ثانیه
                    header('refresh:2;url=index.php');
                } else {
                    $error = 'ایمیل یا رمز عبور اشتباه است';
                }
            }
        } catch (PDOException $e) {
            $error = 'خطا در ارتباط با پایگاه داده: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پنل مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
            padding: 2rem;
        }
        
        .login-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--text-dark);
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        .login-icon {
            font-size: 4rem;
            color: var(--teachers);
            margin-bottom: 1rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating input {
            border: 2px solid var(--primary-light);
            border-radius: var(--border-radius);
            padding: 1rem 0.75rem;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-floating input:focus {
            border-color: var(--teachers);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        
        .form-floating label {
            color: var(--text-medium);
            font-weight: normal;
        }
        
        .login-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--teachers), var(--primary-dark));
            border: none;
            border-radius: var(--border-radius);
            color: var(--white);
            transition: var(--transition);
            margin-top: 1rem;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, var(--primary-dark), var(--teachers));
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: var(--text-medium);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .back-link a:hover {
            color: var(--teachers);
        }
        
        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1>پنل مدیریت</h1>
                <p>لطفاً برای ورود اطلاعات خود را وارد کنید</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="ایمیل" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <label for="email">
                        <i class="bi bi-envelope"></i> ایمیل
                    </label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="رمز عبور" required>
                    <label for="password">
                        <i class="bi bi-lock"></i> رمز عبور
                    </label>
                </div>

                <button type="submit" class="login-btn">
                    <i class="bi bi-box-arrow-in-right"></i> ورود به پنل
                </button>
            </form>

            <div class="back-link">
                <a href="../index.php">
                    <i class="bi bi-arrow-left"></i> بازگشت به سایت اصلی
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 