<?php
include 'layout/header.php';
include 'includes/database.php';
?>

<div class="container app-container">
    <section class="about-section">
        <div class="section-header">
            <h2>درباره آکادمی زبان ما</h2>
        </div>
        
        <div class="about-content">
            <div class="about-text">
                <h3>تاریخچه و ماموریت ما</h3>
                <p>
                    آکادمی آموزش زبان انگلیسی با بیش از ۱۵ سال سابقه درخشان، پیشرو در ارائه خدمات آموزشی با کیفیت در زمینه زبان‌های خارجی است. ما با بهره‌گیری از اساتید مجرب و روش‌های نوین آموزشی، یادگیری زبان را برای شما تبدیل به تجربه‌ای لذت‌بخش می‌کنیم.
                </p>
                <p>
                    ماموریت ما ارائه آموزش‌های کاربردی و مؤثر با توجه به نیازهای متنوع زبان‌آموزان در تمامی سطوح است.
                </p>
                
                <div class="about-features">
                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <h4>۱۰۰۰+ زبان‌آموز</h4>
                        <p>تعداد زبان‌آموزان راضی از خدمات ما</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>۵۰+ استاد مجرب</h4>
                        <p>تیم حرفه‌ای اساتید با مدارک بین‌المللی</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-trophy"></i>
                        <h4>۹۵% رضایت</h4>
                        <p>میزان رضایت زبان‌آموزان از دوره‌ها</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section section">
        <div class="section-header">
            <h2>تیم مدیریتی</h2>
        </div>
        <div class="team-members grid">
            <div class="member card col-4">
                <img src="assets/images/default-teacher.jpg" alt="مدیر عامل">
                <h3>دکتر علی محمدی</h3>
                <p>مدیر عامل و موسس</p>
                <p class="bio">دکترای آموزش زبان از دانشگاه تهران با ۲۰ سال سابقه تدریس</p>
            </div>
            <div class="member card col-4">
                <img src="assets/images/default-teacher.jpg" alt="مدیر آموزشی">
                <h3>دکتر فاطمه احمدی</h3>
                <p>مدیر آموزشی</p>
                <p class="bio">دکترای زبان شناسی از دانشگاه شهید بهشتی با تخصص در روش‌های نوین آموزش</p>
            </div>
            <div class="member card col-4">
                <img src="assets/images/default-teacher.jpg" alt="مدیر اجرایی">
                <h3>مهندس رضا حسینی</h3>
                <p>مدیر اجرایی</p>
                <p class="bio">کارشناسی ارشد مدیریت آموزشی با ۱۰ سال سابقه در مدیریت مراکز آموزشی</p>
            </div>
        </div>
    </section>

    <section class="contact-info-section">
        <div class="section-header">
            <h2>اطلاعات تماس و آدرس</h2>
        </div>
        <div class="contact-wrapper">
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>آدرس دفتر مرکزی</h3>
                    <p>مشهد، بلوار جلال آل احمد، جلال آل احمد ۶۴، دانشگاه سجاد</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>تلفن تماس</h3>
                    <p>۰۵۱-۳۸۴۵۶۷۸۹</p>
                    <p>۰۹۱۵۱۲۳۴۵۶۷</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>پست الکترونیک</h3>
                    <p>info@english-academy.ir</p>
                    <p>support@english-academy.ir</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h3>ساعات کاری</h3>
                    <p>شنبه تا چهارشنبه: ۸ صبح تا ۸ شب</p>
                    <p>پنجشنبه: ۸ صبح تا ۲ بعدازظهر</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layout/footer.php'; ?>