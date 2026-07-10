<?php
session_start();
include 'includes/database.php';
include_once 'includes/media.php';

$search_query = trim($_GET['q'] ?? '');
$search_type = $_GET['type'] ?? 'all';
$results = [];
$total_results = 0;

if (!empty($search_query)) {
    if ($search_type === 'teachers' || $search_type === 'all') {
        // جستجو در اساتید
        $stmt_teachers = $conn->prepare("
            SELECT i.id, i.name, i.bio, i.photo_url,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(r.id) as rating_count
            FROM instructors i
            LEFT JOIN ratings r ON i.id = r.instructor_id
            WHERE i.name LIKE ? OR i.bio LIKE ?
            GROUP BY i.id
            ORDER BY avg_rating DESC
        ");
        $search_term = "%$search_query%";
        $stmt_teachers->execute([$search_term, $search_term]);
        $teachers = $stmt_teachers->fetchAll();
        
        foreach ($teachers as $teacher) {
            $teacher['type'] = 'teacher';
            $teacher['photo_url'] = resolveImagePath($teacher['photo_url'] ?? null, 'assets/images/default-teacher.jpg');
            $results[] = $teacher;
        }
    }
    
    if ($search_type === 'posts' || $search_type === 'all') {
        // جستجو در مقالات
        $stmt_posts = $conn->prepare("
            SELECT p.id, p.title, p.content, p.created_at, p.image_url,
                   i.name as instructor_name
            FROM posts p
            JOIN instructors i ON p.instructor_id = i.id
            WHERE p.title LIKE ? OR p.content LIKE ?
            ORDER BY p.created_at DESC
        ");
        $search_term = "%$search_query%";
        $stmt_posts->execute([$search_term, $search_term]);
        $posts = $stmt_posts->fetchAll();
        
        foreach ($posts as $post) {
            $post['type'] = 'post';
            $post['image_url'] = resolveImagePath($post['image_url'] ?? null, 'assets/images/default-post.jpg');
            $results[] = $post;
        }
    }
    
    $total_results = count($results);
}
?>

<?php include 'layout/header.php'; ?>
    <style>
        .search-container {
            max-width: 900px;
            margin: 2rem auto;
            background: white;
            padding: 3rem;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
        }
        
        .search-input input {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .search-input input:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            background: white;
        }
        
        .search-type {
            min-width: 120px;
        }
        
        .search-type select {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-type select:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }
        
        .search-btn {
            padding: 1.2rem 2.5rem;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }
        
        .search-results {
            margin-top: 2rem;
        }
        
        .result-item {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .result-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            transition: all 0.3s ease;
        }
        
        .result-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: white;
        }
        
        .result-item:hover::before {
            width: 8px;
        }
        
        .result-teacher, .result-post {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }
        
        .result-teacher img, .result-post img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f0f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .result-content {
            flex: 1;
        }
        
        .result-content h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.8rem;
        }
        
        .result-content p {
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 1rem;
        }
        
        .teacher-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .star {
            color: #ffd700;
            font-size: 1.1rem;
        }
        
        .rating-text {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.9rem;
        }
        
        .post-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .post-meta .author {
            color: #4a5568;
            font-weight: 600;
        }
        
        .post-meta .date {
            color: #718096;
        }
        
        .view-profile, .read-more {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }
        
        .view-profile:hover, .read-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
        }
        
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: #4a5568;
        }
        
        .no-results p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .search-stats {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
        }
        
        .search-stats p {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .search-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-input, .search-type {
                min-width: auto;
            }
            
            .result-teacher, .result-post {
                flex-direction: column;
                text-align: center;
            }
            
            .result-teacher img, .result-post img {
                width: 100px;
                height: 100px;
                margin: 0 auto 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container app-container">
        <section class="search-container surface">
            <div class="section-header">
                <h2>جستجو</h2>
                <p>جستجو در اساتید و مقالات</p>
            </div>
            
            <form class="search-form" method="GET">
                <div class="search-input">
                    <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" class="input" placeholder="جستجو..." required>
                </div>
                <div class="search-type">
                    <select name="type" class="select">
                        <option value="all" <?= $search_type === 'all' ? 'selected' : '' ?>>همه</option>
                        <option value="teachers" <?= $search_type === 'teachers' ? 'selected' : '' ?>>اساتید</option>
                        <option value="posts" <?= $search_type === 'posts' ? 'selected' : '' ?>>مقالات</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary search-btn" style="padding:.9rem 1.4rem;">
                    <i class="fas fa-search"></i>
                    جستجو
                </button>
            </form>
            
            <?php if (!empty($search_query)): ?>
                <div class="search-stats">
                    <p><?= $total_results ?> نتیجه برای "<?= htmlspecialchars($search_query) ?>" یافت شد.</p>
                </div>
                
                <?php if (!empty($results)): ?>
                    <div class="search-results">
                        <?php foreach ($results as $result): ?>
                            <div class="result-item">
                                <?php if ($result['type'] === 'teacher'): ?>
                                    <div class="result-teacher">
                                        <img src="<?= htmlspecialchars($result['photo_url']) ?>" alt="<?= htmlspecialchars($result['name']) ?>">
                                        <div class="result-content">
                                            <h3><?= htmlspecialchars($result['name']) ?></h3>
                                            <p><?= htmlspecialchars(substr($result['bio'], 0, 150)) ?>...</p>
                                            <div class="teacher-rating">
                                                <?php
                                                $fullStars = floor($result['avg_rating']);
                                                $halfStar = ($result['avg_rating'] - $fullStars) >= 0.5;
                                                
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($i < $fullStars) {
                                                        echo '<i class="fas fa-star star"></i>';
                                                    } elseif ($i == $fullStars && $halfStar) {
                                                        echo '<i class="fas fa-star-half-alt star"></i>';
                                                    } else {
                                                        echo '<i class="far fa-star star"></i>';
                                                    }
                                                }
                                                ?>
                                                <span class="rating-text"><?= number_format($result['avg_rating'], 1) ?> (<?= $result['rating_count'] ?> رأی)</span>
                                            </div>
                                            <a href="teacher-profile.php?id=<?= $result['id'] ?>" class="view-profile">مشاهده پروفایل</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="result-post">
                                        <img src="<?= htmlspecialchars($result['image_url']) ?>" alt="<?= htmlspecialchars($result['title']) ?>">
                                        <div class="result-content">
                                            <h3><?= htmlspecialchars($result['title']) ?></h3>
                                            <p><?= htmlspecialchars(substr(strip_tags($result['content']), 0, 150)) ?>...</p>
                                            <div class="post-meta">
                                                <span class="author"><?= htmlspecialchars($result['instructor_name']) ?></span>
                                                <span class="date"><?= htmlspecialchars($result['created_at']) ?></span>
                                            </div>
                                            <a href="post.php?id=<?= $result['id'] ?>" class="read-more">ادامه مطلب</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>نتیجه‌ای برای "<?= htmlspecialchars($search_query) ?>" یافت نشد.</p>
                        <p>لطفاً کلمات کلیدی دیگری را امتحان کنید.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
    
    <?php include 'layout/footer.php'; ?>
