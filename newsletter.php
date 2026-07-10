<?php
session_start();
include 'layout/header.php';
include 'includes/database.php';
include_once 'includes/media.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $name = trim($_POST['name'] ?? '');
    
    if (empty($email)) {
        $message = "لطفاً ایمیل خود را وارد کنید";
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "لطفاً یک ایمیل معتبر وارد کنید";
        $message_type = 'error';
    } else {
        // Check if already subscribed
        $stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $message = "این ایمیل قبلاً در خبرنامه ثبت شده است";
            $message_type = 'warning';
        } else {
            // Add to newsletter subscribers
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email, name, subscribed_at) VALUES (?, ?, NOW())");
            if ($stmt->execute([$email, $name])) {
                $message = "با موفقیت در خبرنامه عضو شدید!";
                $message_type = 'success';
            } else {
                $message = "خطا در ثبت‌نام. لطفاً دوباره تلاش کنید";
                $message_type = 'error';
            }
        }
    }
}

// Get latest posts for newsletter preview
$stmt = $conn->prepare("
    SELECT p.*, i.name as instructor_name
    FROM posts p
    LEFT JOIN instructors i ON p.instructor_id = i.id
    ORDER BY p.created_at DESC
    LIMIT 5
");
$stmt->execute();
$latest_posts = $stmt->fetchAll();

// Get top teachers for newsletter preview
$stmt = $conn->prepare("
    SELECT i.id, i.name, i.bio, i.photo_url,
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as rating_count
    FROM instructors i
    LEFT JOIN ratings r ON i.id = r.instructor_id
    GROUP BY i.id
    ORDER BY avg_rating DESC, rating_count DESC
    LIMIT 3
");
$stmt->execute();
$top_teachers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خبرنامه - آکادمی آموزش زبان انگلیسی</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .newsletter-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .newsletter-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .newsletter-header h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        .newsletter-header p {
            color: #666;
            line-height: 1.6;
        }
        
        .newsletter-form {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            color: white;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-row input {
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
        }
        
        .newsletter-btn {
            width: 100%;
            padding: 1rem;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .newsletter-btn:hover {
            background: #ee5a24;
            transform: translateY(-2px);
        }
        
        .newsletter-preview {
            margin-top: 3rem;
        }
        
        .preview-section {
            margin-bottom: 2rem;
        }
        
        .preview-section h3 {
            color: #333;
            margin-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0.5rem;
        }
        
        .preview-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .preview-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .preview-item h4 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .preview-item p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .benefits {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .benefit-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .benefit-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .benefit-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .benefit-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .benefit-desc {
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .benefits {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'layout/header.php'; ?>
    
    <div class="container">
        <div class="newsletter-container">
            <div class="newsletter-header">
                <h1><i class="fas fa-envelope"></i> عضویت در خبرنامه</h1>
                <p>با عضویت در خبرنامه ما، از جدیدترین مقالات، دوره‌های آموزشی، اخبار و تخفیف‌های ویژه مطلع شوید.</p>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $message_type === 'error' ? 'error' : ($message_type === 'warning' ? 'warning' : 'success') ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="newsletter-form">
                <div class="form-row">
                    <input type="text" name="name" placeholder="نام شما (اختیاری)" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <input type="email" name="email" placeholder="آدرس ایمیل شما *" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <button type="submit" class="newsletter-btn">
                    <i class="fas fa-paper-plane"></i> عضویت در خبرنامه
                </button>
            </form>
            
            <div class="benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="benefit-title">مقالات جدید</div>
                    <div class="benefit-desc">دسترسی به جدیدترین مقالات آموزشی</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="benefit-title">دوره‌های جدید</div>
                    <div class="benefit-desc">اطلاع از دوره‌های آموزشی جدید</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="benefit-title">تخفیف‌های ویژه</div>
                    <div class="benefit-desc">دسترسی به تخفیف‌های انحصاری</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="benefit-title">نکات آموزشی</div>
                    <div class="benefit-desc">نکات و ترفندهای یادگیری زبان</div>
                </div>
            </div>
            
            <div class="newsletter-preview">
                <div class="preview-section">
                    <h3><i class="fas fa-newspaper"></i> جدیدترین مقالات</h3>
                    <?php foreach ($latest_posts as $post): ?>
                        <div class="preview-item">
                            <h4><?= htmlspecialchars($post['title']) ?></h4>
                            <p><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                            <small style="color: #999;">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($post['instructor_name']) ?> | 
                                <i class="fas fa-calendar"></i> <?= date('Y/m/d', strtotime($post['created_at'])) ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="preview-section">
                    <h3><i class="fas fa-chalkboard-teacher"></i> اساتید برتر</h3>
                    <?php foreach ($top_teachers as $teacher): ?>
                        <div class="preview-item">
                            <h4><?= htmlspecialchars($teacher['name']) ?></h4>
                            <p><?= htmlspecialchars($teacher['bio']) ?></p>
                            <?php if ($teacher['avg_rating'] > 0): ?>
                                <div style="margin-top: 0.5rem;">
                                    <span style="color: #ffd700;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $teacher['avg_rating'] ? '' : 'far' ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                    <span style="color: #666; margin-right: 0.5rem;">
                                        <?= number_format($teacher['avg_rating'], 1) ?> (<?= $teacher['rating_count'] ?> رأی)
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'layout/footer.php'; ?>
</body>
</html>
