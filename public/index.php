<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#066755">
    <meta name="description" content="بوابة خبرة  - منصة  عمانية رائدة تقدم خدمات  متميزة">
    <title>بوابة خبرة  - اصنع طريقك للنجاح</title>
    
    <!-- Preconnect for faster loading -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Optimized -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS - Load synchronously for mobile stability -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/responsive-enhancements.css" rel="stylesheet">
    <link href="assets/css/premium-design.css" rel="stylesheet">
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <!-- Fallback CSS to ensure visibility -->
    <style>
        /* Ensure content is visible */
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif !important;
            background-color: #f8f9fa !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            width: 100%;
        }

        /* Force white numbers in Premium Stats cards */
        .premium-stats-section .stat-number {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            background: none !important;
            -webkit-background-clip: unset !important;
            background-clip: unset !important;
        }
        
        /* Responsive Container Fix */
        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }
        
        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }
        
        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }
        
        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }
        
        @media (min-width: 1400px) {
            .container {
                max-width: 1320px;
            }
        }
        
        /* Performance Optimizations */
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        .lazy {
            opacity: 1;
            transition: opacity 0.3s;
        }
        
        .lazy.loaded {
            opacity: 1;
        }
        
        /* Reduced motion preference - disable animations that may cause visibility issues */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Mobile animation safety - ensure animations complete */
        @media (max-width: 768px) {
            /* Mobile Rendering Stability - Ensure all sections are visible */
            section,
            .premium-hero,
            .premium-offers-section,
            .creative-about-section,
            .premium-services-section,
            .premium-stats-section,
            .premium-team-section,
            .premium-testimonials-section,
            .premium-contact-section,
            .premium-faq-section,
            .premium-footer,
            footer {
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Ensure section content is always visible */
            section *,
            footer * {
                visibility: visible;
            }
            
            .fade-in-up,
            .slide-in-left,
            .slide-in-right,
            .scale-in,
            [class*="animate"] {
                animation-fill-mode: forwards !important;
                opacity: 1 !important;
            }
            
            /* Prevent GPU rendering issues on mobile */
            section,
            .service-card-new,
            .team-card,
            .testimonial-card,
            .contact-form,
            .faq-item,
            .premium-footer {
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }
        }
        
        .hero-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%) !important;
            min-height: 100vh !important;
            padding-top: 80px !important;
        }
        
        .btn-primary {
            background-color: #066755 !important;
            border-color: #066755 !important;
        }
        
        .btn-accent {
            background: linear-gradient(90deg, #E74C60 0%, #EE6676 100%) !important;
            border: none !important;
            color: white !important;
        }
        
        .text-primary {
            color: #066755 !important;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #066755 0%, #044a3d 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }
        
        /* Force visibility of all content */
        .hero-content,
        .hero-content h1,
        .hero-content h2,
        .hero-content p,
        .btn,
        .feature-icon {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
</head>
<body class="has-premium-navbar">
    <?php $currentPage = 'index'; include 'includes/navbar.php'; ?>

    <!-- Hero Section - Premium Design -->
    <section id="home" class="premium-hero">
        <div class="container">
            <div class="row align-items-center py-5">
                <!-- Hero Content -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="hero-badge">
                        <i class="fas fa-star"></i>
                        <span>منصة  عمانية رائدة</span>
                    </div>
                    
                    <h1>
                        <span class="highlight">بوابة خبرة</span>
                    </h1>
                    
                    <p class="subtitle">منصتك للتعلّم والتطوير وصناعة المستقبل</p>
                    
                    <p class="description">
                        منذ عام 2020م ونحن نُمكّن الأفراد والمؤسسات، عبر دورات تخصصية وخدمات متكاملة 
                        تجمع بين التعليم، والتطوير، والتوجيه، والتنفيذ.
                    </p>
                    
                    <div class="hero-buttons">
                        <a href="courses.php" class="btn-hero-primary">
                            <i class="fas fa-graduation-cap"></i>
                            ابدأ رحلتك 
                        </a>
                        <a href="services.php" class="btn-hero-secondary">
                            <i class="fas fa-info-circle"></i>
                            تعرف على خدماتنا
                        </a>
                    </div>
                    
                    <!-- Hero Features -->
                    <div class="hero-features">
                        <div class="hero-feature">
                            <div class="hero-feature-icon green">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="hero-feature-content">
                                <h6>تعليم متميز</h6>
                                <span>خبرات أكاديمية عمانية</span>
                            </div>
                        </div>
                        
                        <div class="hero-feature">
                            <div class="hero-feature-icon red">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="hero-feature-content">
                                <h6>حلول مبتكرة</h6>
                                <span>أساليب  حديثة</span>
                            </div>
                        </div>
                        
                        <div class="hero-feature">
                            <div class="hero-feature-icon green">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="hero-feature-content">
                                <h6>دعم شامل</h6>
                                <span>مرافقة في كل خطوة</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Card -->
                <div class="col-lg-6">
                    <div class="hero-card">
                        <div class="hero-card-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        
                        <h4>رحلة التعلم تبدأ هنا</h4>
                        <p>انضم إلى آلاف الطلاب الذين حققوا أهدافهم معنا</p>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" data-count="3000">3000+</div>
                                <div class="stat-label">طالب مستفيد</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-count="150">150+</div>
                                <div class="stat-label">خبير أكاديمي</div>
                            </div>
                        </div>
                        
                        <div class="progress-stat">
                            <div class="stat-header">
                                <span class="stat-text">نسبة النجاح</span>
                                <span class="stat-value">95%</span>
                            </div>
                            <div class="premium-progress">
                                <div class="bar" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Offers Section -->
    <section class="premium-offers-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header text-center">
                <span class="section-badge">
                    <i class="fas fa-fire"></i>
                    عروض حصرية
                </span>
                <h2 class="section-title">عروضنا <span class="highlight">المميزة</span></h2>
                <p class="section-subtitle">اكتشف أحدث العروض والخدمات  المصممة خصيصاً لنجاحك</p>
            </div>
            
            <!-- Offers Grid -->
            <div class="offers-grid">
                <?php
                require_once __DIR__ . '/includes/functions.php';
                try {
                    $pdo = getPDO();
                    $ads = $pdo->query("SELECT * FROM ads WHERE is_active = 1 ORDER BY display_order ASC LIMIT 3")->fetchAll();
                    
                    if (count($ads) > 0):
                        $icons = ['fa-graduation-cap', 'fa-book-reader', 'fa-award'];
                        $colors = ['primary', 'accent', 'primary'];
                        $i = 0;
                        foreach($ads as $ad):
                ?>
                <div class="offer-card <?= $colors[$i % 3] ?>">
                    <div class="offer-icon">
                        <i class="fas <?= $icons[$i % 3] ?>"></i>
                    </div>
                    <div class="offer-content">
                        <h3 class="offer-title"><?= htmlspecialchars($ad['title']) ?></h3>
                        <p class="offer-description"><?= htmlspecialchars($ad['description']) ?></p>
                        <?php if ($ad['button_link']): ?>
                        <a href="<?= htmlspecialchars($ad['button_link']) ?>" class="offer-btn">
                            <?= htmlspecialchars($ad['button_text'] ?: 'اعرف المزيد') ?>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="offer-decoration"></div>
                </div>
                <?php 
                            $i++;
                        endforeach;
                    else:
                ?>
                <!-- Default Offers -->
                <div class="offer-card primary">
                    <div class="offer-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="offer-content">
                        <span class="offer-badge">الأكثر طلبًا</span>
                        <h3 class="offer-title">خدماتنا الأكثر طلبًا</h3>
                        <p class="offer-description">انضم لـ +500 مستفيد من خدماتنا الاحترافية المعتمدة.</p>
                        <ul class="offer-features">
                            <li><i class="fas fa-check"></i> حلول احترافية معتمدة</li>
                            <li><i class="fas fa-check"></i> خبراء متخصصون</li>
                            <li><i class="fas fa-check"></i> متابعة مستمرة</li>
                        </ul>
                        <a href="services.php" class="offer-btn">
                            تصفح الخدمات
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="offer-decoration"></div>
                </div>

                <div class="offer-card accent featured">
                    <div class="offer-ribbon">خصم 30%</div>
                    <div class="offer-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="offer-content">
                        <span class="offer-badge">خصم 30%</span>
                        <h3 class="offer-title">عرض الموسم الحصري</h3>
                        <p class="offer-description">وفر 30% عند اشتراكك في باقة الدورات المتكاملة اليوم.</p>
                        <div class="offer-price">
                            <span class="old-price">150 ر.ع</span>
                            <span class="new-price">105 ر.ع</span>
                        </div>
                        <a href="courses.php" class="offer-btn">
                            سجّل الآن
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="offer-decoration"></div>
                </div>

                <div class="offer-card primary">
                    <div class="offer-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="offer-content">
                        <span class="offer-badge">الأكثر إقبالًا</span>
                        <h3 class="offer-title">ورشنا الأكثر إقبالًا</h3>
                        <p class="offer-description">احجز مقعدك في الورشة القادمة وتعلّم مع نخبة الخبراء.</p>
                        <ul class="offer-features">
                            <li><i class="fas fa-check"></i> ورش تطبيقية تفاعلية</li>
                            <li><i class="fas fa-check"></i> أماكن محدودة</li>
                            <li><i class="fas fa-check"></i> شهادات حضور</li>
                        </ul>
                        <a href="courses.php" class="offer-btn">
                            احجز مقعدك
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="offer-decoration"></div>
                </div>
                <?php
                    endif;
                } catch (PDOException $e) {
                ?>
                <!-- Fallback Offers -->
                <div class="offer-card primary">
                    <div class="offer-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="offer-content">
                        <h3 class="offer-title">خدماتنا الأكثر طلبًا</h3>
                        <p class="offer-description">انضم لـ +500 مستفيد من خدماتنا الاحترافية المعتمدة.</p>
                        <a href="services.php" class="offer-btn">تصفح الخدمات <i class="fas fa-arrow-left"></i></a>
                    </div>
                </div>
                <div class="offer-card accent featured">
                    <div class="offer-ribbon">خصم 30%</div>
                    <div class="offer-icon"><i class="fas fa-percent"></i></div>
                    <div class="offer-content">
                        <h3 class="offer-title">عرض الموسم الحصري</h3>
                        <p class="offer-description">وفر 30% عند اشتراكك في باقة الدورات المتكاملة اليوم.</p>
                        <a href="courses.php" class="offer-btn">سجّل الآن <i class="fas fa-arrow-left"></i></a>
                    </div>
                </div>
                <div class="offer-card primary">
                    <div class="offer-icon"><i class="fas fa-users"></i></div>
                    <div class="offer-content">
                        <h3 class="offer-title">ورشنا الأكثر إقبالًا</h3>
                        <p class="offer-description">احجز مقعدك في الورشة القادمة وتعلّم مع نخبة الخبراء.</p>
                        <a href="courses.php" class="offer-btn">احجز مقعدك <i class="fas fa-arrow-left"></i></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Premium About Section - Creative Design -->
    <section id="about" class="creative-about-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Right Side - Creative Visual -->
                <div class="col-lg-6 order-lg-2">
                    <div class="creative-visual-wrapper">
                        <!-- Main Image Card -->
                        <div class="main-visual-card">
                            <div class="visual-gradient-bg"></div>
                            <div class="visual-pattern"></div>
                            <div class="central-icon">
                                <div class="icon-ring ring-1"></div>
                                <div class="icon-ring ring-2"></div>
                                <div class="icon-ring ring-3"></div>
                                <div class="icon-center">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Left Side - Text Content -->
                <div class="col-lg-6 order-lg-1">
                    <div class="creative-about-content">
                        <div class="section-tag">
                            <span class="tag-dot"></span>
                            <span>تعرّف علينا</span>
                        </div>
                        
                        <h2 class="creative-title">
                            من <span class="gradient-text">نحن؟</span>
                        </h2>
                        
                        <div class="intro-box">
                            <p>
                                في <strong>بوابة خبرة</strong>، نؤمن بأن التعليم والدعم هما أساس صناعة التغيير.
                            </p>
                        </div>
                        
                        <p class="creative-description">
                            نحن فريق من الأكاديميين العُمانيين، نجمع بين المعرفة والخبرة لنقدّمَ حلولًا تعليمية
                            وخدمات تنفيذية تُلهم الأفراد وتُمكّن المؤسسات، رؤيتنا أن نكون شريكًا في صناعة أثر مستدام
                            ينعكس على المجتمع بأكمله.
                        </p>
                        
                        <!-- Creative Features -->
                        <div class="creative-features">
                            <div class="creative-feature">
                                <div class="feature-icon-wrapper">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="feature-details">
                                    <h6>دورات تخصصية</h6>
                                    <span>محتوى تعليمي متميز</span>
                                </div>
                            </div>
                            
                            <div class="creative-feature">
                                <div class="feature-icon-wrapper accent">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                                <div class="feature-details">
                                    <h6>خدمات متكاملة</h6>
                                    <span>حلول متكاملة ونتائج ملموسة</span>
                                </div>
                            </div>
                            
                            <div class="creative-feature">
                                <div class="feature-icon-wrapper">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="feature-details">
                                    <h6>حلول مبتكرة</h6>
                                    <span>تمكين المؤسسات</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="creative-cta-group">
                            <a href="about.php" class="cta-primary">
                                <span>اكتشف قصتنا</span>
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <a href="team.php" class="cta-secondary">
                                <i class="fas fa-users"></i>
                                <span>تعرّف على فريقنا</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Values Header -->
            <div class="text-center mb-5 mt-5">
                <h3 class="display-6 fw-bold mb-3">لماذا تختارنا؟</h3>
                <p class="lead text-muted">
                    الركائز التي نبني عليها شراكتنا معك
                </p>
            </div>

            <!-- Values Grid -->
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="value-card card-secondary bg-white p-4 rounded-3 shadow-sm text-center h-100" style="transition: all 0.3s ease;">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <img src="assets/icons/credibility.png" alt="المصداقية" style="width: 32px; height: 32px;">
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #066755;">المصداقية</h5>
                        <p class="text-muted">
                            ضمان التواصل الآمن والتنفيذ عالي الجودة
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card card-accent bg-white p-4 rounded-3 shadow-sm text-center h-100" style="transition: all 0.3s ease;">
                        <div class="bg-accent bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <img src="assets/icons/appreciation_partnership.png" alt="التقدير" style="width: 32px; height: 32px;">
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #066755;">التقدير</h5>
                        <p class="text-muted">
                            تعزيز الاحترام المتبادل والتقدير في جميع التعاملات
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card card-secondary bg-white p-4 rounded-3 shadow-sm text-center h-100" style="transition: all 0.3s ease;">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <img src="assets/icons/determination.png" alt="المرونة" style="width: 32px; height: 32px;">
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #066755;">المرونة</h5>
                        <p class="text-muted">
                            نتمسك بالإلتزام والعزيمة لتحقيق أفضل النتائج
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card card-accent bg-white p-4 rounded-3 shadow-sm text-center h-100" style="transition: all 0.3s ease;">
                        <div class="bg-accent bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <img src="assets/icons/motivation.png" alt="الدافع" style="width: 32px; height: 32px;">
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #066755;">الدافع</h5>
                        <p class="text-muted">
                            نتبنى التغيير والتحول كركيزة أساسية في نهجنا
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Services Section -->
    <section id="services" class="premium-services-section">
        <div class="container">
            <!-- Section Header -->
            <div class="services-header">
                <div class="header-badge">
                    <i class="fas fa-cogs"></i>
                    <span>ما نقدمه لك</span>
                </div>
                <h2 class="services-title"><strong>خدماتنا</strong> <strong class="gradient-text">المتميزة</strong></h2>
                <p class="services-subtitle">
                    نقدم حلولا  وتنفيذية تلبي احتياجاتك وتساعدك على تحقيق أهدافك
                </p>
            </div>

            <!-- Services Grid - New Design -->
            <div class="services-grid">
                <!-- Service 1 -->
                <div class="service-item">
                    <div class="service-card-new">
                        <div class="service-icon-wrapper green">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="service-content">
                            <h4 class="service-name">للطلاب الجامعيين والخريجين</h4>
                            <p class="service-desc">خدمات متخصصة لدعم مسيرتك الأكاديمية والمهنية</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i> دروس خصوصية</li>
                                <li><i class="fas fa-check"></i> دعم أكاديمي</li>
                                <li><i class="fas fa-check"></i> استشارات مهنية</li>
                            </ul>
                        </div>
                        <a href="services.php" class="service-link">
                            <span>اعرف المزيد</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="service-item featured">
                    <div class="service-card-new">
                        <div class="featured-badge">الأكثر طلباً</div>
                        <div class="service-icon-wrapper coral">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="service-content">
                            <h4 class="service-name">للباحثين عن عمل والموظفين</h4>
                            <p class="service-desc">برامج تطويرية لتعزيز مهاراتك وفرصك المهنية</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i> ورش تدريبية</li>
                                <li><i class="fas fa-check"></i> تطوير مهارات</li>
                                <li><i class="fas fa-check"></i> توجيه عملي</li>
                            </ul>
                        </div>
                        <a href="services.php" class="service-link featured">
                            <span>اعرف المزيد</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="service-item">
                    <div class="service-card-new">
                        <div class="service-icon-wrapper blue">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="service-content">
                            <h4 class="service-name">لأصحاب العمل المستقل</h4>
                            <p class="service-desc">خدمات إبداعية وتنفيذية تساعدك على النمو والتوسع</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i> خدمات إبداعية</li>
                                <li><i class="fas fa-check"></i> خدمات تنفيذية</li>
                                <li><i class="fas fa-check"></i> دعم النمو والتوسع</li>
                            </ul>
                        </div>
                        <a href="services.php" class="service-link">
                            <span>اعرف المزيد</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <!-- Service 4 -->
                <div class="service-item">
                    <div class="service-card-new">
                        <div class="service-icon-wrapper purple">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="service-content">
                            <h4 class="service-name">للمؤسسات الحكومية والشركات</h4>
                            <p class="service-desc">برامج تدريبية وخدمات تنفيذية تعزز الكفاءة والتنافسية</p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i> برامج تدريبية</li>
                                <li><i class="fas fa-check"></i> خدمات تنفيذية</li>
                                <li><i class="fas fa-check"></i> تعزيز الكفاءة والتنافسية</li>
                            </ul>
                        </div>
                        <a href="services.php" class="service-link">
                            <span>اعرف المزيد</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="services-cta">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="cta-text">
                        <h4>هل تحتاج خدمة مخصصة؟</h4>
                        <p>تواصل معنا لنناقش احتياجاتك ونقدم لك الحل المناسب</p>
                    </div>
                </div>
                <div class="cta-buttons">
                    <a href="#contact" class="cta-btn primary">
                        <i class="fas fa-phone"></i>
                        <span>تواصل معنا</span>
                    </a>
                    <a href="courses.php" class="cta-btn secondary">
                        <i class="fas fa-book"></i>
                        <span>تصفح الكورسات</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Stats Section -->
    <section class="premium-stats-section">
        <div class="stats-bg-decoration"></div>
        <div class="container">
            <!-- Section Header -->
            <div class="stats-header">
                <span class="stats-badge">
                    <i class="fas fa-chart-bar"></i>
                    إنجازاتنا
                </span>
                <h2 class="stats-title">أرقام تروي <span class="gradient-text">قصة نجاحنا</span></h2>
                <p class="stats-subtitle">منذ انطلاقتنا عام 2020م ونحن نسعى لتحقيق التميز</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards-wrapper">
                <div class="stats-card">
                    <div class="stat-icon-box yellow">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">+2200</div>
                    <div class="stat-label">طالب مستفيد</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill yellow" style="width: 90%"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stat-icon-box green">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-number">+320</div>
                    <div class="stat-label">خبير ومشرف</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill green" style="width: 80%"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stat-icon-box coral">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-number">+13</div>
                    <div class="stat-label">مجال تخصصي</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill coral" style="width: 65%"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stat-icon-box purple">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">+10</div>
                    <div class="stat-label">موسم تدريبي</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill purple" style="width: 50%"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stat-icon-box blue">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="stat-number">+10</div>
                    <div class="stat-label">شراكة محلية</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill blue" style="width: 55%"></div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stat-icon-box orange">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">+10</div>
                    <div class="stat-label">مؤسسة متعاونة</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill orange" style="width: 45%"></div>
                    </div>
                </div>
            </div>

            <!-- Testimonial Card -->
            <div class="premium-testimonial">
                <div class="testimonial-quote-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <div class="testimonial-content">
                    <p class="testimonial-text">
                        بوابة خبرة غيرت مسار حياتي  والمهنية. الدعم والتوجيه الذي تلقيته كان استثنائياً، 
                        والخبراء هنا يتمتعون بمهارات عالية وخبرة واسعة في مجالاتهم.
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h6>أحمد المعمري</h6>
                            <span>طالب جامعي - كلية الهندسة</span>
                        </div>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Team Section -->
    <section id="team" class="premium-team-section">
        <div class="container">
            <!-- Section Header -->
            <div class="team-header">
                <span class="team-badge-header">
                    <i class="fas fa-users"></i>
                    فريق العمل
                </span>
                <h2 class="team-title">تعرّف على <span class="gradient-text">فريقنا</span></h2>
                <p class="team-subtitle">
                    نخبة من الخبراء الأكاديميين المتميزين يقودون رحلة التعلم والتطوير
                </p>
            </div>

            <!-- Team Grid -->
            <div class="team-grid">
                <!-- Team Member 1 - Featured -->
                <div class="team-member featured">
                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-avatar">
                                <div class="avatar-img">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-status online"></div>
                            </div>
                            <div class="featured-badge">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                        
                        <div class="member-info">
                            <h4 class="member-name">د. هيثم العامري</h4>
                            <span class="member-role">المؤسس والرئيس التنفيذي</span>
                            <span class="member-specialty">تكنولوجيا التعليم الإلكتروني</span>
                        </div>
                        
                        <p class="member-bio">
                            خبير في تطوير المناهج الرقمية وتقنيات التعليم الحديثة مع أكثر من 15 عاماً من الخبرة
                        </p>
                        
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-member">
                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-avatar coral">
                                <div class="avatar-img">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-status online"></div>
                            </div>
                        </div>
                        
                        <div class="member-info">
                            <h4 class="member-name">أ. أماني الكلبانية</h4>
                            <span class="member-role">مديرة الدورات والدروس</span>
                            <span class="member-specialty">التربية وعلم النفس التعليمي</span>
                        </div>
                        
                        <p class="member-bio">
                            متخصصة في تصميم البرامج  وتطوير استراتيجيات التعلم الفعال
                        </p>
                        
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-member">
                    <div class="member-card">
                        <div class="member-header">
                            <div class="member-avatar purple">
                                <div class="avatar-img">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="member-status online"></div>
                            </div>
                        </div>
                        
                        <div class="member-info">
                            <h4 class="member-name">د. صفية العمرية</h4>
                            <span class="member-role">مديرة الدعم النفسي</span>
                            <span class="member-specialty">علم النفس الإرشادي</span>
                        </div>
                        
                        <p class="member-bio">
                            خبيرة في الإرشاد النفسي والمهني مع تركيز خاص على دعم الطلاب
                        </p>
                        
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Join Team CTA -->
            <div class="join-team-cta">
                <div class="cta-decoration"></div>
                <div class="cta-inner">
                    <div class="cta-icon-box">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="cta-content">
                        <h4>انضم إلى فريقنا</h4>
                        <p>هل لديك خبرة أكاديمية؟ نبحث دائماً عن خبراء متميزين</p>
                    </div>
                    <a href="join.php" class="cta-button">
                        <span>قدّم طلبك</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Testimonials Section -->
    <section class="premium-testimonials-section">
        <div class="testimonials-bg-pattern"></div>
        <div class="container">
            <!-- Section Header -->
            <div class="testimonials-header">
                <span class="testimonials-badge">
                    <i class="fas fa-comments"></i>
                    آراء العملاء
                </span>
                <h2 class="testimonials-title">قصص <span class="gradient-text">نجاح ملهمة</span></h2>
                <p class="testimonials-subtitle">اكتشف تجارب من سبقوك وحققوا أهدافهم معنا</p>
            </div>

            <!-- Testimonials Grid -->
            <div class="testimonials-grid">
                <!-- Testimonial 1 - Featured -->
                <div class="testimonial-card featured">
                    <div class="quote-icon">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "بوابة خبرة غيرت مسار حياتي . الدعم والتوجيه الذي تلقيته كان استثنائياً، والخبراء يتمتعون بمهارات عالية وخبرة واسعة في مجالاتهم."
                    </p>
                    <div class="testimonial-author-info">
                        <div class="author-avatar green">
                            <span>أ.م</span>
                        </div>
                        <div class="author-details">
                            <h5>أحمد المعمري</h5>
                            <span>طالب جامعي - كلية الهندسة</span>
                        </div>
                        <div class="verified-badge">
                            <i class="fas fa-check-circle"></i>
                            موثق
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="quote-icon small">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "ساعدتني بوابة خبرة في الحصول على وظيفة أحلامي. الإرشاد المهني والدورات التدريبية كانت في غاية الأهمية."
                    </p>
                    <div class="testimonial-author-info">
                        <div class="author-avatar coral">
                            <span>ف.ش</span>
                        </div>
                        <div class="author-details">
                            <h5>فاطمة الشكيلية</h5>
                            <span>خريجة - إدارة أعمال</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="quote-icon small">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "تعاونا مع بوابة خبرة لتدريب فريقنا وكانت النتائج مذهلة. برامج تدريبية احترافية ومدربون أكفاء."
                    </p>
                    <div class="testimonial-author-info">
                        <div class="author-avatar purple">
                            <span>م.ح</span>
                        </div>
                        <div class="author-details">
                            <h5>محمد الحارثي</h5>
                            <span>مدير موارد بشرية</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Trust Indicators -->
            <div class="trust-indicators">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="trust-info">
                        <span class="trust-number">+2200</span>
                        <span class="trust-label">عميل سعيد</span>
                    </div>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="trust-info">
                        <span class="trust-number">4.9</span>
                        <span class="trust-label">تقييم متوسط</span>
                    </div>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="trust-info">
                        <span class="trust-number">100%</span>
                        <span class="trust-label">نسبة الرضا</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section - Premium Design -->
    <section id="contact" class="premium-contact-section">
        <div class="contact-bg-pattern"></div>
        <div class="container position-relative">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="contact-header-badge">
                    <i class="fas fa-headset"></i>
                    <span>أرسل رسالتك</span>
                </div>
                <h2 class="contact-header-title">نحن هنا <span>لمساعدتك</span></h2>
                <p class="contact-header-subtitle">
                    فريقنا جاهز للإجابة على استفساراتك وتقديم أفضل الحلول 
                </p>
            </div>

            <div class="row g-4">
                <!-- Contact Form -->
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form-card">
                        <div class="form-header">
                            <h4><i class="fas fa-paper-plane"></i> أرسل رسالتك</h4>
                            <p>سنرد عليك خلال 24 ساعة</p>
                        </div>
                        
                        <form id="contactForm" method="POST" class="premium-form">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-group">
                                        <div class="input-icon"><i class="fas fa-user"></i></div>
                                        <input type="text" class="form-control premium-input" id="name" name="name" 
                                               placeholder="الاسم الكامل" required>
                                        <label for="name">الاسم الكامل</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-group">
                                        <div class="input-icon"><i class="fas fa-phone"></i></div>
                                        <input type="tel" class="form-control premium-input" id="phone" name="phone" 
                                               placeholder="رقم الهاتف" required>
                                        <label for="phone">رقم الهاتف</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating-group">
                                        <div class="input-icon"><i class="fas fa-envelope"></i></div>
                                        <input type="email" class="form-control premium-input" id="email" name="email" 
                                               placeholder="البريد الإلكتروني" required>
                                        <label for="email">البريد الإلكتروني</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating-group">
                                        <div class="input-icon"><i class="fas fa-graduation-cap"></i></div>
                                        <select class="form-control premium-input" id="field" name="field" onchange="updateSpecializations()">
                                            <option value="">اختر المجال</option>
                                            <option value="it">هندسة الاتصالات وتقنية المعلومات</option>
                                            <option value="chemical">الهندسة الكيميائية والبتروكيماويات</option>
                                            <option value="construction">الإنشاءات</option>
                                            <option value="english">اللغة الانجليزية</option>
                                            <option value="design">التصميم</option>
                                            <option value="accounting">إدارة الحسابات والتسويق</option>
                                        </select>
                                        <label for="field">المجال</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-group">
                                        <div class="input-icon"><i class="fas fa-cog"></i></div>
                                        <select class="form-control premium-input" id="service" name="service">
                                            <option value="">اختر الخدمة</option>
                                            <option value="lessons">دروس أكاديمية</option>
                                            <option value="courses">دورات تدريبية</option>
                                            <option value="consulting">استشارات</option>
                                            <option value="research">كتابة بحوث</option>
                                        </select>
                                        <label for="service">الخدمة</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating-group textarea-group">
                                        <div class="input-icon"><i class="fas fa-comment-dots"></i></div>
                                        <textarea class="form-control premium-input" id="message" name="message" rows="4" 
                                                  placeholder="رسالتك" required></textarea>
                                        <label for="message">رسالتك</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="submit-btn">
                                        <span class="btn-text">
                                            <i class="fas fa-paper-plane"></i>
                                            أرسل رسالتك
                                        </span>
                                        <span class="btn-loader">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick CTA -->
            <div class="quick-cta-section">
                <div class="quick-cta-content">
                    <div class="cta-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="cta-text">
                        <h4>تحتاج رد سريع؟</h4>
                        <p>تواصل معنا مباشرة عبر الواتساب</p>
                    </div>
                    <a href="https://wa.me/96892332749" class="cta-button">
                        <i class="fab fa-whatsapp"></i>
                        ابدأ المحادثة
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section - Premium Design -->
    <section id="faq" class="premium-faq-section">
        <div class="faq-bg-decoration">
            <div class="faq-circle-1"></div>
            <div class="faq-circle-2"></div>
        </div>
        
        <div class="container position-relative">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="faq-header-badge">
                    <i class="fas fa-comments"></i>
                    <span>مركز المساعدة</span>
                </div>
                <h2 class="faq-header-title">الأسئلة <span class="faq-gradient-text">الشائعة</span></h2>
                <p class="faq-header-subtitle">إجابات واضحة على استفساراتك الأكثر شيوعاً</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- FAQ Items Container -->
                    <div class="faq-container">
                        <!-- FAQ Item 1 -->
                        <div class="faq-item" id="faq1">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <div class="faq-icon-wrapper">
                                    <i class="fas fa-building"></i>
                                </div>
                                <span class="faq-question-text">ما هي بوابة خبرة؟</span>
                                <div class="faq-toggle">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <p>بوابة خبرة هي منصة رقمية عُمانية مرخصة، تقدم حلولاً تعليمية وخدمات تنفيذية وتخصصية متكاملة. نعمل منذ عام 2020م على تمكين الأفراد والمؤسسات من خلال برامج تدريبية وإرشادية، وتحويل المعرفة إلى نتائج ملموسة تخدم تطلعاتكم وتدعم نمو مؤسساتكم.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="faq-item" id="faq2">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <div class="faq-icon-wrapper blue">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <span class="faq-question-text">ما الفرق بين الدورات والخدمات؟</span>
                                <div class="faq-toggle">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <div class="faq-comparison">
                                        <div class="comparison-item">
                                            <div class="comparison-icon courses">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <h6>الدورات</h6>
                                            <p>برامج  مهيكلة بمحتوى محدد ومدة زمنية معينة، مثل الدورات التخصصية والورش التدريبية.</p>
                                        </div>
                                        <div class="comparison-item">
                                            <div class="comparison-icon services">
                                                <i class="fas fa-cogs"></i>
                                            </div>
                                            <h6>الخدمات</h6>
                                            <p>حلول مخصصة حسب احتياجك، مثل التصميم والتسويق، المواقع الإلكترونية والتطبيقات، والتصميم الداخلي والخارجي.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="faq-item" id="faq3">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <div class="faq-icon-wrapper purple">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <span class="faq-question-text">كيف أنضم كخبير أو مشرف؟</span>
                                <div class="faq-toggle">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <div class="faq-steps">
                                        <div class="step-item">
                                            <span class="step-number">1</span>
                                            <span>زر صفحة "<a href="join.php">انضم إلينا</a>"</span>
                                        </div>
                                        <div class="step-item">
                                            <span class="step-number">2</span>
                                            <span>اختر "انضم كخبير" أو "شارك كمشرف"</span>
                                        </div>
                                        <div class="step-item">
                                            <span class="step-number">3</span>
                                            <span>املأ النموذج بمعلوماتك وخبراتك</span>
                                        </div>
                                        <div class="step-item">
                                            <span class="step-number">4</span>
                                            <span>سنتواصل معك خلال <strong>48 ساعة</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="faq-item" id="faq4">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <div class="faq-icon-wrapper gold">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <span class="faq-question-text">ما آلية الدفع؟</span>
                                <div class="faq-toggle">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <p>نوفر <strong>عدة طرق دفع مرنة:</strong></p>
                                    <div class="payment-methods">
                                        <div class="payment-method">
                                            <i class="fas fa-university"></i>
                                            <span>التحويل البنكي</span>
                                        </div>
                                        <div class="payment-method">
                                            <i class="fas fa-globe"></i>
                                            <span>الدفع الإلكتروني</span>
                                        </div>
                                        <div class="payment-method">
                                            <i class="fas fa-hand-holding-usd"></i>
                                            <span>الدفع عند الاستلام</span>
                                        </div>
                                    </div>
                                    <p class="mt-3">كما نقدم خطط تقسيط للدورات الطويلة. للمزيد، <a href="#contact">تواصل معنا</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Banner -->
                    <div class="faq-cta-banner">
                        <div class="faq-cta-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="faq-cta-content">
                            <h5>لم تجد إجابتك؟</h5>
                            <p>فريق الدعم جاهز لمساعدتك على مدار الساعة</p>
                        </div>
                        <a href="#contact" class="faq-cta-button">
                            تواصل معنا
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function toggleFaq(element) {
        const item = element.closest('.faq-item');
        if (!item) return;
        
        const isActive = item.classList.contains('active');
        
        // Close all FAQ items
        document.querySelectorAll('.faq-item').forEach(faq => {
            faq.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!isActive) {
            item.classList.add('active');
        }
    }
    </script>

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
                        <a href="#contact">تواصل معنا</a>
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
    
    <!-- Performance Optimizations -->
    <script>
        // Lazy load images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px'
            });
            
            images.forEach(img => imageObserver.observe(img));
            
            // Preload critical resources
            const preloadLink = document.createElement('link');
            preloadLink.rel = 'preload';
            preloadLink.as = 'style';
            preloadLink.href = 'assets/css/style.css';
            document.head.appendChild(preloadLink);
        });
        
        // Remove unused CSS on mobile
        if (window.innerWidth < 768) {
            document.querySelectorAll('link[media="print"]').forEach(link => {
                setTimeout(() => link.media = 'all', 100);
            });
        }
    </script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js?v=<?php echo time(); ?>"></script>
    
    <!-- Enhanced Navbar Script -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.custom-navbar');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        });
        
        // Close navbar on link click (mobile)
        document.querySelectorAll('.enhanced-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navbar = document.querySelector('.navbar-collapse');
                if (navbar && navbar.classList.contains('show')) {
                    const instance = bootstrap.Collapse.getInstance(navbar);
                    if (instance) instance.hide();
                }
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#!') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offset = 80;
                        const targetPosition = target.offsetTop - offset;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    </script>
    
    <!-- Dynamic Contact Form Script -->
    <script>
        
        // تحديث الخدمات بناءً على المجال المختار
        function updateSpecializations() {
            const field = document.getElementById('field')?.value;
            const serviceSelect = document.getElementById('service');
            if (!serviceSelect) return;
            
            // تحديث خيارات الخدمة بناءً على المجال
            serviceSelect.innerHTML = '<option value="">اختر الخدمة</option>';
            
            const servicesByField = {
                'it': ['دروس خصوصية', 'دورة تدريبية', 'استشارة تقنية', 'بناء مشروع'],
                'chemical': ['دروس خصوصية', 'مشروع تخرج', 'استشارة هندسية'],
                'construction': ['دروس خصوصية', 'إدارة مشاريع', 'استشارة هندسية'],
                'english': ['دروس خصوصية', 'دورة IELTS', 'دورة محادثة', 'كتابة أكاديمية'],
                'design': ['تصميم جرافيك', 'تصميم UI/UX', 'موشن جرافيك'],
                'accounting': ['دروس خصوصية', 'استشارة مالية', 'تسويق رقمي']
            };
            
            const services = servicesByField[field] || ['دروس أكاديمية', 'دورات تدريبية', 'استشارات', 'كتابة بحوث'];
            services.forEach(s => {
                const option = document.createElement('option');
                option.value = s;
                option.textContent = s;
                serviceSelect.appendChild(option);
            });
        }
        
        // معالجة إرسال النموذج
        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.querySelector('.btn-text').style.display = 'none';
                submitBtn.querySelector('.btn-loader').style.display = 'inline-flex';
                submitBtn.disabled = true;
            }
            
            // بناء رسالة واتساب بالبيانات
            const name = formData.get('name') || '';
            const phone = formData.get('phone') || '';
            const email = formData.get('email') || '';
            const field = formData.get('field') || '';
            const service = formData.get('service') || '';
            const message = formData.get('message') || '';
            
            const waMessage = `مرحباً، أنا ${name}\nالهاتف: ${phone}\nالبريد: ${email}\nالمجال: ${field}\nالخدمة: ${service}\nالرسالة: ${message}`;
            
            // عرض رسالة نجاح ثم فتح واتساب
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم إرسال طلبك بنجاح! سنتواصل معك قريباً.<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            this.parentNode.insertBefore(alertDiv, this);
            
            setTimeout(() => { if (alertDiv.parentNode) alertDiv.remove(); }, 5000);
            
            this.reset();
            
            if (submitBtn) {
                submitBtn.querySelector('.btn-text').style.display = 'inline-flex';
                submitBtn.querySelector('.btn-loader').style.display = 'none';
                submitBtn.disabled = false;
            }
            
            // فتح واتساب بالبيانات
            window.open('https://wa.me/96892332749?text=' + encodeURIComponent(waMessage), '_blank');
        });
    </script>
</body>
</html>
