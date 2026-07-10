// Main JavaScript file for English Language Learning Website

document.addEventListener('DOMContentLoaded', function() {
    // Theme persistence and toggle
    const rootElement = document.documentElement;
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'dark') {
        rootElement.classList.add('theme-dark');
    } else if (storedTheme === 'light') {
        rootElement.classList.remove('theme-dark');
    }

    const themeToggleBtn = document.getElementById('themeToggle');
    if (themeToggleBtn) {
        const updateToggleIcon = () => {
            const icon = themeToggleBtn.querySelector('i');
            if (!icon) return;
            if (rootElement.classList.contains('theme-dark')) {
                themeToggleBtn.setAttribute('aria-label', 'تغییر به حالت روشن');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                themeToggleBtn.setAttribute('aria-label', 'تغییر به حالت تاریک');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        };

        updateToggleIcon();

        themeToggleBtn.addEventListener('click', () => {
            rootElement.classList.toggle('theme-dark');
            const isDark = rootElement.classList.contains('theme-dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateToggleIcon();
        });
    }
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.teacher-card, .post-card, .course-card, .benefit-item');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length >= 2) {
                    performSearch(query);
                }
            }, 300);
        });
    }
    
    // Star rating functionality
    const starRatings = document.querySelectorAll('.star-rating');
    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('input');
        const labels = rating.querySelectorAll('label');
        
        stars.forEach((star, index) => {
            star.addEventListener('change', function() {
                // Update visual stars
                labels.forEach((label, labelIndex) => {
                    if (labelIndex <= index) {
                        label.style.color = '#ffd700';
                    } else {
                        label.style.color = '#ddd';
                    }
                });
            });
        });
        
        // Hover effects
        labels.forEach((label, index) => {
            label.addEventListener('mouseenter', function() {
                labels.forEach((l, i) => {
                    if (i <= index) {
                        l.style.color = '#ffd700';
                    }
                });
            });
            
            label.addEventListener('mouseleave', function() {
                const checkedStar = rating.querySelector('input:checked');
                if (checkedStar) {
                    const checkedIndex = Array.from(stars).indexOf(checkedStar);
                    labels.forEach((l, i) => {
                        if (i <= checkedIndex) {
                            l.style.color = '#ffd700';
                        } else {
                            l.style.color = '#ddd';
                        }
                    });
                } else {
                    labels.forEach(l => l.style.color = '#ddd');
                }
            });
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ff6b6b';
                    field.style.boxShadow = '0 0 0 3px rgba(255, 107, 107, 0.1)';
                } else {
                    field.style.borderColor = '#e0e0e0';
                    field.style.boxShadow = 'none';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('لطفاً تمام فیلدهای ضروری را پر کنید', 'error');
            }
        });
    });
    
    // Loading animation
    function showLoading(element) {
        const loading = document.createElement('div');
        loading.className = 'loading';
        loading.innerHTML = '<div class="spinner"></div>';
        element.appendChild(loading);
    }
    
    function hideLoading(element) {
        const loading = element.querySelector('.loading');
        if (loading) {
            loading.remove();
        }
    }
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
        
        // Close button functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.remove();
        });
    }
    
    // Scroll to top button functionality
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    if (scrollToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });
        
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Enhanced Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    const body = document.body;
    
    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
            body.classList.toggle('menu-open');
            
            // Prevent body scroll when menu is open
            if (navLinks.classList.contains('active')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenuBtn.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                body.classList.remove('menu-open');
                body.style.overflow = '';
            }
        });
        
        // Close menu when clicking on a link
        const navLinkItems = navLinks.querySelectorAll('.nav-link');
        navLinkItems.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                body.classList.remove('menu-open');
                body.style.overflow = '';
            });
        });
    }
    
    // Lazy loading for images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Search functionality
    function performSearch(query) {
        const searchResults = document.querySelector('.search-results');
        if (searchResults) {
            showLoading(searchResults);
            
            // Simulate search (replace with actual AJAX call)
            setTimeout(() => {
                hideLoading(searchResults);
                // Update search results here
            }, 500);
        }
    }
    
    // Add CSS for new elements
    const style = document.createElement('style');
    style.textContent = `

        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            padding: 1rem;
            gap: 1rem;
        }
        
        .notification-message {
            flex: 1;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }
        
        .notification-success {
            border-left: 4px solid #28a745;
        }
        
        .notification-error {
            border-left: 4px solid #dc3545;
        }
        
        .notification-warning {
            border-left: 4px solid #ffc107;
        }
        
        .notification-info {
            border-left: 4px solid #17a2b8;
        }
        
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #667eea, #764ba2);
                flex-direction: column;
                padding: 1rem;
            }
            
            .nav-links.active {
                display: flex;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
    
    // Add tooltip styles
    const tooltipStyle = document.createElement('style');
    tooltipStyle.textContent = `
        .tooltip {
            position: absolute;
            background: #333;
            color: white;
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
            z-index: 10000;
            pointer-events: none;
        }
        
        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
        }
    `;
    document.head.appendChild(tooltipStyle);
    
    // Course filtering functionality
    const courseFilterButtons = document.querySelectorAll('.course-filters .filter-btn');
    const courseCards = document.querySelectorAll('.course-card');

    courseFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            courseFilterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');

            const filter = button.getAttribute('data-filter');

            courseCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Teacher filtering functionality
    const teacherFilterButtons = document.querySelectorAll('.teacher-filters .filter-btn');
    const teacherCards = document.querySelectorAll('.teacher-card');

    teacherFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            teacherFilterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');

            const filter = button.getAttribute('data-filter');

            teacherCards.forEach(card => {
                const rating = parseFloat(card.getAttribute('data-rating'));
                const experience = parseInt(card.getAttribute('data-experience'));
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'top-rated':
                        show = rating >= 4.5;
                        break;
                    case 'experienced':
                        show = experience >= 50;
                        break;
                    case 'new':
                        show = experience < 20;
                        break;
                }
                
                if (show) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Post filtering functionality
    const postFilterButtons = document.querySelectorAll('.post-filters .filter-btn');
    const postCards = document.querySelectorAll('.post-card');

    postFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            postFilterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');

            const filter = button.getAttribute('data-filter');

            postCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Course card hover effects
    courseCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Course enrollment buttons
    const enrollButtons = document.querySelectorAll('.btn-enroll');
    enrollButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const courseTitle = this.closest('.course-card').querySelector('h3').textContent;
            showNotification(`ثبت‌نام در دوره "${courseTitle}" با موفقیت انجام شد!`, 'success');
        });
    });

    // Course preview buttons
    const previewButtons = document.querySelectorAll('.btn-preview');
    previewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const courseTitle = this.closest('.course-card').querySelector('h3').textContent;
            showNotification(`پیش‌نمایش دوره "${courseTitle}" در حال بارگذاری...`, 'info');
        });
    });

    // Teacher contact buttons
    const contactButtons = document.querySelectorAll('.btn-contact');
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const teacherName = this.closest('.teacher-card').querySelector('h3').textContent;
            showNotification(`در حال اتصال به ${teacherName}...`, 'info');
        });
    });

    // Post bookmark buttons
    const bookmarkButtons = document.querySelectorAll('.btn-bookmark');
    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postTitle = this.closest('.post-card').querySelector('h3').textContent;
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                showNotification(`مقاله "${postTitle}" به نشانه‌ها اضافه شد`, 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                showNotification(`مقاله "${postTitle}" از نشانه‌ها حذف شد`, 'info');
            }
        });
    });

    console.log('English Language Learning Website JavaScript loaded successfully!');
});
