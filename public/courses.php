<?php
// جلب الكورسات من قاعدة البيانات
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

try {
  $pdo = getPDO();
  $courses = $pdo->query("
    SELECT c.*, u.name AS instructor
    FROM courses c
    JOIN users u ON u.id = c.instructor_id
    ORDER BY c.created_at DESC
  ")->fetchAll() ?: [];
} catch (Throwable $e) {
  $courses = [];
}

$currentUser = current_user();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#E74C60">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="<?php echo gmdate('D, d M Y H:i:s') . ' GMT'; ?>">
    <title>مواهب خبرة - منصة التعلم الإلكتروني</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Bahij Janna -->
    <link href="https://fonts.googleapis.com/css2?family=Bahij+Janna:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="assets/css/responsive-enhancements.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <!-- Talents Page Specific CSS -->
    <style>
        /* Talents Page Specific Styles */
        .talents-hero {
            background: linear-gradient(135deg, #E74C60 0%, #EE6676 50%, #066755 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .talents-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.3;
        }
        
        .course-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }
        
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(231, 76, 96, 0.2);
        }
        
        .course-thumbnail {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        
        .course-thumbnail::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(231, 76, 96, 0.1), rgba(6, 103, 85, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .course-card:hover .course-thumbnail::after {
            opacity: 1;
        }
        
        .course-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .course-content {
            padding: 20px;
        }
        
        .course-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.4;
            height: 2.8rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .course-instructor {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .course-rating {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .stars {
            color: #ffc107;
            margin-left: 5px;
        }
        
        .rating-text {
            color: #666;
            font-size: 0.9rem;
            margin-right: 5px;
        }
        
        .course-price {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .price-current {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-color);
        }
        
        .price-original {
            font-size: 1rem;
            color: #999;
            text-decoration: line-through;
            margin-right: 10px;
        }
        
        .course-stats {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .enroll-btn {
            width: 100%;
            background: linear-gradient(90deg, #E74C60 0%, #EE6676 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .enroll-btn:hover {
            background: linear-gradient(90deg, #D63E52 0%, #E85A6B 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 96, 0.3);
            color: white;
        }
        
        .category-tabs {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 70px;
            z-index: 100;
        }
        
        .category-tab {
            padding: 10px 20px;
            border-radius: 25px;
            margin: 0 5px;
            border: 2px solid transparent;
            background: #f8f9fa;
            color: #666;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .category-tab:hover,
        .category-tab.active {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }
        
        .search-section {
            background: white;
            padding: 30px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .search-box {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 20px 15px 60px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 96, 0.1);
        }
        
        .search-btn {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #D63E52;
            transform: translateY(-50%) scale(1.1);
        }
        
        .featured-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .stats-section {
            background: white;
            padding: 40px 0;
        }
        
        .stat-item-large {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: #666;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .course-thumbnail {
                height: 180px;
            }
            
            .course-content {
                padding: 15px;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .category-tab {
                padding: 8px 15px;
                font-size: 0.9rem;
                margin: 0 2px;
            }
        }
        
        @media (max-width: 576px) {
            .talents-hero {
                min-height: 50vh;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-label {
                font-size: 0.95rem;
            }
            
            .course-thumbnail {
                height: 160px;
            }
            
            .section-title h2 {
                font-size: 1.6rem;
            }
            
            .category-tab {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 374.98px) {
            .talents-hero {
                min-height: 45vh;
            }
            
            .stat-number {
                font-size: 1.6rem;
            }
            
            .course-thumbnail {
                height: 140px;
            }
            
            .section-title h2 {
                font-size: 1.4rem;
            }
            
            .category-tab {
                padding: 5px 10px;
                font-size: 0.8rem;
            }
            
            .course-content {
                padding: 12px;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <!-- Navigation -->
    <?php $currentPage = 'courses'; include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="talents-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="text-white">
                        <h1 class="display-4 fw-bold mb-4">مواهب خبرة</h1>
                        <h2 class="h3 mb-4">منصة التعلم الإلكتروني الرائدة</h2>
                        <p class="lead mb-4">
                            اكتشف آلاف الدورات  المتخصصة من أفضل الخبراء الأكاديميين العمانيين. 
                            تعلم في أي وقت ومن أي مكان مع شهادات معتمدة.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <button class="btn btn-light btn-lg px-4 py-3">
                                <i class="fas fa-play me-2"></i>
                                ابدأ التعلم الآن
                            </button>
                            <button class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-eye me-2"></i>
                                تصفح الدورات
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="position-relative">
                            <div class="bg-white bg-opacity-10 rounded-4 p-5 backdrop-blur">
                                <i class="fas fa-laptop-code text-white" style="font-size: 8rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="ابحث عن الدورات، المدرسين، أو المواضيع...">
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Category Tabs -->
    <section class="category-tabs">
        <div class="container">
            <div class="d-flex justify-content-center flex-wrap">
                <a href="#" class="category-tab active">جميع الدورات</a>
                <a href="#" class="category-tab">البرمجة والتقنية</a>
                <a href="#" class="category-tab">الأعمال والإدارة</a>
                <a href="#" class="category-tab">اللغات</a>
                <a href="#" class="category-tab">التصميم</a>
                <a href="#" class="category-tab">العلوم</a>
                <a href="#" class="category-tab">التطوير الشخصي</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item-large">
                        <span class="stat-number"><?= count($courses) ?>+</span>
                        <div class="stat-label">دورة </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item-large">
                        <span class="stat-number">10K+</span>
                        <div class="stat-label">طالب نشط</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item-large">
                        <span class="stat-number">150+</span>
                        <div class="stat-label">خبير معلم</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item-large">
                        <span class="stat-number">98%</span>
                        <div class="stat-label">نسبة الرضا</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="featured-section">
        <div class="container">
            <div class="section-title">
                <h2>الدورات المميزة</h2>
                <p>اكتشف أفضل الدورات  المختارة بعناية من قبل خبرائنا</p>
            </div>
            
            <div class="row g-4">
                <?php if (empty($courses)): ?>
                    <!-- No Courses -->
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap text-muted" style="font-size: 5rem;"></i>
                            <h4 class="text-muted mt-4">لا توجد كورسات متاحة حالياً</h4>
                            <p class="text-muted">سيتم إضافة الكورسات قريباً</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <!-- Course Card from Database -->
                        <div class="col-lg-3 col-md-6">
                            <div class="course-card">
                                <?php if (!empty($course['thumbnail_url'])): ?>
                                    <div class="course-thumbnail" style="background-image: url('<?= htmlspecialchars($course['thumbnail_url']) ?>');">
                                        <div class="course-badge">جديد</div>
                                    </div>
                                <?php else: ?>
                                    <div class="course-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="course-badge">جديد</div>
                                        <div class="position-absolute top-50 start-50 translate-middle text-white">
                                            <i class="fas fa-graduation-cap" style="font-size: 3rem;"></i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="course-content">
                                    <h5 class="course-title"><?= htmlspecialchars($course['title']) ?></h5>
                                    <div class="course-instructor">
                                        <i class="fas fa-user me-1"></i>
                                        <?= htmlspecialchars($course['instructor']) ?>
                                    </div>
                                    <div class="course-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="rating-text">4.5 (1,234)</span>
                                    </div>
                                    <div class="course-stats">
                                        <div class="stat-item">
                                            <i class="fas fa-clock"></i>
                                            <span>40 ساعة</span>
                                        </div>
                                        <div class="stat-item">
                                            <i class="fas fa-users"></i>
                                            <span>2,450 طالب</span>
                                        </div>
                                    </div>
                                    <div class="course-price">
                                        <span class="price-current"><?= number_format((float)($course['price'] ?? 0), 2) ?> ر.ع</span>
                                        <span class="price-original"><?= number_format((float)($course['price'] ?? 0) * 2, 2) ?> ر.ع</span>
                                    </div>
                                    <a href="course.php?id=<?= (int)$course['id'] ?>" class="enroll-btn">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض الكورس
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Premium Footer -->
    <footer class="premium-footer">
        <!-- Decorative Wave -->
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#066755" d="M0,60 C360,120 1080,0 1440,60 L1440,120 L0,120 Z"></path>
            </svg>
        </div>
        
        <div class="footer-main">
            <div class="container">
                <div class="row g-5">
                    <!-- Brand Column -->
                    <div class="col-lg-4">
                        <div class="footer-brand">
                            <div class="brand-logo">
                                <img src="assets/icons/logo.png" alt="بوابة خبرة">
                                <div class="brand-text">
                                    <h4>بوابة خبرة</h4>
                                </div>
                            </div>
                            <p class="brand-desc">
                                منصتك للتعلّم والتطوير وصناعة المستقبل. نُمكّن الأفراد والمؤسسات منذ 2020م عبر دورات تخصصية وخدمات متكاملة.
                            </p>
                            
                            <!-- Contact Cards -->
                            <div class="contact-cards">
                                <a href="tel:+96892332749" class="contact-card">
                                    <div class="contact-icon phone">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="contact-details">
                                        <span class="label">اتصل بنا</span>
                                        <span class="value" dir="ltr">+968 92332749</span>
                                    </div>
                                </a>
                                <a href="mailto:info.expertplatform@gmail.com" class="contact-card">
                                    <div class="contact-icon email">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-details">
                                        <span class="label">البريد الإلكتروني</span>
                                        <span class="value">info.expertplatform@gmail.com</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Links Columns -->
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <!-- Quick Links -->
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title">
                                        <i class="fas fa-link"></i>
                                        روابط سريعة
                                    </h5>
                                    <ul>
                                        <li><a href="index.php"><i class="fas fa-chevron-left"></i> الرئيسية</a></li>
                                        <li><a href="about.php"><i class="fas fa-chevron-left"></i> من نحن</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> خدماتنا</a></li>
                                        <li><a href="courses.php"><i class="fas fa-chevron-left"></i> الكورسات</a></li>
                                        <li><a href="team.php"><i class="fas fa-chevron-left"></i> فريقنا</a></li>
                                        <li><a href="join.php"><i class="fas fa-chevron-left"></i> انضم إلينا</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Services -->
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title">
                                        <i class="fas fa-cogs"></i>
                                        خدماتنا
                                    </h5>
                                    <ul>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم والتسويق</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> المواقع الإلكترونية والتطبيقات</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم الداخلي والخارجي</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> الدروس الخصوصية</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> وغيرها</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CTA Column -->
                    <div class="col-lg-3">
                        <div class="footer-cta">
                            <h5 class="cta-title">
                                <i class="fas fa-comments"></i>
                                تواصل معنا
                            </h5>
                            <p class="cta-desc">نسعد بالإجابة على استفساراتك</p>
                            
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/96892332749" class="whatsapp-btn" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp"></i>
                                <span>تواصل عبر واتساب</span>
                            </a>
                            
                            <!-- Social Icons -->
                            <div class="social-icons">
                                <span class="social-label">تابعنا على</span>
                                <div class="icons-row">
                                    <a href="https://instagram.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon instagram" title="انستقرام">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="https://linkedin.com/company/exp-edu" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" title="لينكدإن">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="https://youtube.com/@exp_edu" target="_blank" rel="noopener noreferrer" class="social-icon youtube" title="يوتيوب">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                    <a href="https://x.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon twitter x-social" title="إكس" aria-label="إكس">
                                        <span class="x-social-mark">X</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Newsletter -->
                            <div class="newsletter-mini">
                                <span>اشترك في النشرة البريدية</span>
                                <div class="newsletter-form">
                                    <input type="email" placeholder="بريدك الإلكتروني" aria-label="البريد الإلكتروني">
                                    <button type="button" aria-label="اشتراك"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-content">
                    <div class="copyright">
                        <span>© <?= date('Y') ?> بوابة خبرة</span>
                        <span class="separator">|</span>
                        <span>جميع الحقوق محفوظة</span>
                    </div>
                    <div class="bottom-links">
                        <a href="#">سياسة الخصوصية</a>
                        <a href="#">الشروط والأحكام</a>
                        <a href="index.php#contact">تواصل معنا</a>
                    </div>
                    <div class="made-with">
                        <span>صُنع بـ</span>
                        <i class="fas fa-heart"></i>
                        <span>في عُمان</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Category tabs functionality
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Search functionality
            document.querySelector('.search-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = this.value;
                    alert('البحث عن: ' + searchTerm);
                }
            });
        });
    </script>
</body>
</html>