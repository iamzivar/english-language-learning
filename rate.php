<?php
// فایل: rate.php
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // در اینجا باید شناسه کاربر از سیستم گرفته بشه (برای نمونه، مقدار ثابت قرار میدم)
    $user_id = 1;  // فرض بر این است که کاربر در حال حاضر وارد شده است
    $instructor_id = $_POST['instructor_id'];
    $rating = $_POST['rating'];

    // ذخیره امتیاز در دیتابیس
    $stmt = $conn->prepare("INSERT INTO ratings (user_id, instructor_id, rating) VALUES (?, ?, ?)");

    if ($stmt->execute([$user_id, $instructor_id, $rating])) {
        // پیامی مبنی بر موفقیت ارسال می‌شود
        echo "امتیاز با موفقیت ارسال شد!";
        // پس از ارسال موفقیت‌آمیز، کاربر به صفحه پروفایل استاد هدایت می‌شود
        header("Location: teacher-profile.php?id=" . $instructor_id);
        exit();
    } else {
        // پیامی برای خطا در ارسال امتیاز
        echo "خطا در ارسال امتیاز!";
    }
}
?>

<?php include 'layout/header.php'; ?>

<div class="main-container">
    <div class="rating-form-container">
        <h1>امتیازدهی به استاد</h1>

        <form id="rating-form" method="POST" class="rating-form">
            <label for="rating">امتیاز خود را انتخاب کنید:</label>

            <!-- سیستم امتیازدهی ستاره‌ای -->
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
            </div>

            <!-- شناسه استاد رو برای ارسال در پست دریافت می‌کنیم -->
            <input type="hidden" name="instructor_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

            <button type="submit" class="btn btn-primary">ارسال امتیاز</button>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
