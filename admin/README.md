# 🎓 پنل مدیریت English Language Learning

## 📋 فهرست مطالب
- [نصب و راه‌اندازی](#نصب-و-راه‌اندازی)
- [ایجاد Admin پیش‌فرض](#ایجاد-admin-پیش‌فرض)
- [ورود به سیستم](#ورود-به-سیستم)
- [ویژگی‌ها](#ویژگی‌ها)
- [ساختار فایل‌ها](#ساختار-فایل‌ها)

## 🚀 نصب و راه‌اندازی

### پیش‌نیازها:
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر
- Apache/Nginx
- XAMPP/WAMP/MAMP

### مراحل نصب:

1. **کپی کردن فایل‌ها:**
   ```bash
   # کپی کردن پوشه admin به مسیر پروژه
   cp -r admin/ /path/to/your/project/
   ```

2. **تنظیم دیتابیس:**
   - دیتابیس `english_language_learning` را ایجاد کنید
   - جدول `users` باید شامل فیلدهای زیر باشد:
     - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
     - `username` (VARCHAR)
     - `email` (VARCHAR)
     - `password` (VARCHAR)
     - `role` (VARCHAR) - 'admin' یا 'user'
     - `created_at` (TIMESTAMP)

3. **تنظیم اتصال دیتابیس:**
   فایل `../includes/database.php` را ویرایش کنید:
   ```php
   $host = "localhost";
   $dbname = "english_language_learning";
   $username = "root";
   $password = "";
   ```

## 🚀 راه‌اندازی سریع

### روش 1: راه‌اندازی خودکار (توصیه شده)
```
http://localhost/english_language_learning/admin/setup.php
```

### روش 2: راه‌اندازی دستی

1. **بررسی دیتابیس:**
   ```
   http://localhost/english_language_learning/admin/check_database.php
   ```

2. **ایجاد admin پیش‌فرض:**
   ```
   http://localhost/english_language_learning/admin/create_default_admin.php
   ```

### اطلاعات پیش‌فرض:
- 📧 ایمیل: `admin@example.com`
- 👤 نام کاربری: `admin`
- 🔑 رمز عبور: `admin123`

## 🔐 ورود به سیستم

1. **مراجعه به صفحه لاگین:**
   ```
   http://localhost/english_language_learning/admin/admin_login.php
   ```

2. **ورود با اطلاعات admin:**
   - ایمیل: `admin@example.com`
   - رمز عبور: `admin123`

3. **هدایت خودکار به داشبورد:**
   پس از ورود موفق، به طور خودکار به `index.php` هدایت می‌شوید.

## 📁 فایل‌های جدید

- `setup.php` - راه‌اندازی خودکار کامل
- `check_database.php` - بررسی ساختار دیتابیس
- `admin_login.php` - صفحه ورود مستقل
- `logout.php` - خروج از سیستم
- `includes/auth_check.php` - بررسی احراز هویت

## ✨ ویژگی‌ها

### 🔒 امنیت:
- ✅ احراز هویت کامل
- ✅ بررسی نقش admin
- ✅ Session management
- ✅ Password hashing
- ✅ SQL injection protection

### 🎨 طراحی:
- ✅ طراحی زیبا و مدرن
- ✅ فونت فارسی Vazir
- ✅ Bootstrap 5
- ✅ Bootstrap Icons
- ✅ Responsive design
- ✅ انیمیشن‌های نرم

### 📊 مدیریت:
- ✅ مدیریت کاربران
- ✅ مدیریت اساتید
- ✅ مدیریت پست‌ها
- ✅ مدیریت کامنت‌ها
- ✅ مدیریت امتیازات
- ✅ مدیریت پیام‌های تماس

### 🔧 عملیات:
- ✅ افزودن، ویرایش، حذف
- ✅ جستجو و فیلتر
- ✅ نمایش آمار
- ✅ مدیریت فایل‌ها

## 📁 ساختار فایل‌ها

```
admin/
├── index.php                 # داشبورد اصلی
├── admin_login.php          # صفحه ورود
├── logout.php               # خروج از سیستم
├── create_default_admin.php # ایجاد admin پیش‌فرض
├── README.md                # راهنما
│
├── includes/
│   ├── auth_check.php       # بررسی احراز هویت
│   ├── admin_header.php     # هدر مشترک
│   ├── admin_sidebar.php    # منوی کناری
│   └── admin_footer.php     # فوتر مشترک
│
├── assets/
│   ├── css/
│   │   └── admin.css        # استایل‌های admin
│   └── fonts/
│       ├── Vazir.woff2      # فونت فارسی
│       ├── Vazir.woff
│       ├── Vazir.ttf
│       └── Vazir.eot
│
├── admin_users.php          # مدیریت کاربران
├── admin_teachers.php       # مدیریت اساتید
├── admin_posts.php          # مدیریت پست‌ها
├── admin_comments.php       # مدیریت کامنت‌ها
├── admin_ratings.php        # مدیریت امتیازات
├── contact_messages.php     # مدیریت پیام‌ها
│
├── create_user.php          # افزودن کاربر
├── create_teacher.php       # افزودن استاد
├── create_post.php          # افزودن پست
│
├── edit_user.php            # ویرایش کاربر
├── edit_teacher.php         # ویرایش استاد
├── edit_post.php            # ویرایش پست
│
├── delete_user.php          # حذف کاربر
├── delete_teacher.php       # حذف استاد
├── delete_post.php          # حذف پست
├── delete_comment.php       # حذف کامنت
│
└── view.php                 # مشاهده پیام
```

## 🔧 تنظیمات اضافی

### تغییر رمز عبور admin:
1. از طریق پنل مدیریت: `admin_users.php`
2. یا مستقیماً در دیتابیس

### تغییر اطلاعات admin:
- نام کاربری: `admin_users.php`
- ایمیل: `admin_users.php`

### امنیت بیشتر:
- تغییر رمز عبور پیش‌فرض
- فعال‌سازی HTTPS
- تنظیم session timeout
- محدود کردن IP

## 🆘 پشتیبانی

در صورت بروز مشکل:
1. بررسی لاگ‌های PHP
2. بررسی اتصال دیتابیس
3. بررسی مجوزهای فایل
4. بررسی تنظیمات session

## 📝 لایسنس

این پروژه تحت لایسنس MIT منتشر شده است. 