<?php
require __DIR__ . '/includes/functions.php';
// لا نحتاج auth.php - الصفحة متاحة للجميع
session_start();

$pdo = getPDO();
$id = intval($_GET['id'] ?? 0);
$course = $pdo->prepare("SELECT c.*, u.name as instructor FROM courses c JOIN users u ON u.id = c.instructor_id WHERE c.id=?");
$course->execute([$id]);
$c = $course->fetch();

if(!$c) { 
    http_response_code(404); 
    echo "<!DOCTYPE html><html><head><title>غير موجود</title></head><body style='text-align:center;padding:100px;'><h1>الكورس غير موجود</h1><a href='courses.php'>العودة للكورسات</a></body></html>"; 
    exit; 
}

$lessons = $pdo->prepare("SELECT * FROM lessons WHERE course_id=? ORDER BY created_at");
$lessons->execute([$id]);
$lessons = $lessons->fetchAll();

// التحقق من المستخدم الحالي (اختياري)
$currentUser = isset($_SESSION['user_id']) ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? '',
    'role' => $_SESSION['user_role'] ?? 'student'
] : null;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#066755">
    <meta name="description" content="<?= htmlspecialchars($c['short_description'] ?? $c['title']) ?>">
    <title><?= htmlspecialchars($c['title']) ?> - بوابة خبرة </title>
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <!-- Navbar & Footer CSS -->
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        :root {
            --primary-color: #066755;
            --secondary-color: #044a3d;
            --accent-color: #E74C60;
            --accent-light: #EE6676;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 50%, var(--primary-color) 100%);
            color: white;
            padding: 60px 0 40px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.15)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.5;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .course-title {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .course-meta {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.15);
            padding: 10px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
        }
        
        .course-meta-item i {
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            box-shadow: 0 15px 50px rgba(0,0,0,0.12);
            transform: translateY(-5px);
        }
        
        .content-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #f1f5f9;
        }
        
        .content-card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .content-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        /* Sidebar Card */
        .sidebar-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            position: sticky;
            top: 100px;
        }
        
        .course-thumbnail {
            width: 100%;
            height: 280px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }
        
        .sidebar-content {
            padding: 30px;
        }
        
        .price-tag {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .price-amount {
            font-size: 2.5rem;
            font-weight: 900;
        }
        
        .price-label {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            border-radius: 12px;
            background: #f8fafc;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: #e2e8f0;
            transform: translateX(-5px);
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .enroll-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(6, 103, 85, 0.3);
        }
        
        .enroll-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(6, 103, 85, 0.5);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        /* Lesson Items */
        .lessons-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .lesson-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .lesson-card:hover {
            background: white;
            border-color: var(--primary-color);
            transform: translateX(-5px);
            box-shadow: 0 5px 20px rgba(6, 103, 85, 0.15);
        }
        
        .lesson-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .lesson-info {
            flex: 1;
        }
        
        .lesson-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .lesson-description {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .lesson-actions {
            display: flex;
            gap: 10px;
        }
        
        .watch-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .watch-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(6, 103, 85, 0.4);
            color: white;
        }
        
        .badge-count {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .course-title {
                font-size: 2rem;
            }
            
            .sidebar-card {
                position: static;
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .course-title {
                font-size: 1.7rem;
            }
            
            .course-meta {
                gap: 15px;
            }
            
            .lesson-card {
                flex-direction: column;
                text-align: center;
            }
            
            .lesson-number {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .content-card {
                padding: 25px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 40px 0 30px;
            }
            
            .course-title {
                font-size: 1.5rem;
            }
            
            .price-amount {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <!-- Navbar -->
    <?php $currentPage = 'course'; include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="course-title"><?= htmlspecialchars($c['title']) ?></h1>
                        
                        <?php if ($c['short_description']): ?>
                            <p class="lead mb-4" style="opacity: 0.95;">
                                <?= htmlspecialchars($c['short_description']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="course-meta">
                            <div class="course-meta-item">
                                <i class="fas fa-user-tie"></i>
                                <span><?= htmlspecialchars($c['instructor']) ?></span>
                            </div>
                            <div class="course-meta-item">
                                <i class="fas fa-video"></i>
                                <span><?= count($lessons) ?> دروس</span>
                            </div>
                            <div class="course-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><?= date('d/m/Y', strtotime($c['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center mt-4 mt-lg-0">
                        <div class="price-tag" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                            <div class="price-amount"><?= number_format($c['price'], 0) ?></div>
                            <div class="price-label">ريال عماني</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container pb-5">
        <div class="row g-4">
            <!-- Main Column -->
            <div class="col-lg-8">
                <!-- About Course -->
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="content-card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2 class="content-card-title">عن هذا الكورس</h2>
                    </div>
                    <div class="course-description" style="line-height: 1.8; color: #475569; font-size: 1.05rem;">
                        <?= nl2br(htmlspecialchars($c['description'])) ?>
                    </div>
                </div>

                <!-- Lessons -->
                <div class="content-card">
                    <div class="content-card-header">
                        <div class="content-card-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h2 class="content-card-title">محتوى الكورس</h2>
                        <span class="badge-count ms-auto"><?= count($lessons) ?> درس</span>
                    </div>
                    
                    <?php if (count($lessons) > 0): ?>
                        <div class="lessons-list">
                            <?php foreach($lessons as $idx => $lesson): ?>
                                <div class="lesson-card">
                                    <div class="lesson-number"><?= $idx + 1 ?></div>
                                    <div class="lesson-info">
                                        <div class="lesson-title">
                                            <i class="fas fa-video me-2" style="color: var(--primary-color);"></i>
                                            <?= htmlspecialchars($lesson['title']) ?>
                                        </div>
                                        <?php if ($lesson['description']): ?>
                                            <div class="lesson-description">
                                                <?= htmlspecialchars($lesson['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="lesson-actions">
                                        <a href="stream.php?key=<?= urlencode($lesson['wasabi_key']) ?>" 
                                           class="watch-btn" 
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            <i class="fas fa-play"></i>
                                            <span>مشاهدة</span>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-video" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                            <h4 style="color: #64748b;">لا توجد دروس متاحة حالياً</h4>
                            <p style="color: #94a3b8;">سيتم إضافة الدروس قريباً</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <?php if ($c['thumbnail_url']): ?>
                        <img src="<?= htmlspecialchars($c['thumbnail_url']) ?>" 
                             alt="<?= htmlspecialchars($c['title']) ?>" 
                             class="course-thumbnail">
                    <?php else: ?>
                        <div class="course-thumbnail" style="display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-graduation-cap" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="sidebar-content">
                        <div class="price-tag">
                            <div class="price-amount"><?= number_format($c['price'], 0) ?></div>
                            <div class="price-label">ريال عماني</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <small style="color: #64748b; display: block;">المدرّس</small>
                                <strong style="color: #1e293b;"><?= htmlspecialchars($c['instructor']) ?></strong>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <div>
                                <small style="color: #64748b; display: block;">عدد الدروس</small>
                                <strong style="color: #1e293b;"><?= count($lessons) ?> درس</strong>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div>
                                <small style="color: #64748b; display: block;">تاريخ الإضافة</small>
                                <strong style="color: #1e293b;"><?= date('d/m/Y', strtotime($c['created_at'])) ?></strong>
                            </div>
                        </div>
                        
                        <button class="enroll-btn">
                            <i class="fas fa-shopping-cart me-2"></i>
                            اشترك الآن
                        </button>
                        
                        <div class="text-center mt-3">
                            <small style="color: #94a3b8;">
                                <i class="fas fa-lock me-1"></i>
                                دفع آمن ومضمون
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Premium Footer -->
    <footer class="premium-footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#066755" d="M0,60 C360,120 1080,0 1440,60 L1440,120 L0,120 Z"></path>
            </svg>
        </div>
        <div class="footer-main">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="footer-brand">
                            <div class="brand-logo">
                                <img src="assets/icons/logo.png" alt="بوابة خبرة">
                                <div class="brand-text"><h4>بوابة خبرة</h4></div>
                            </div>
                            <p class="brand-desc">منصتك للتعلّم والتطوير وصناعة المستقبل. نُمكّن الأفراد والمؤسسات منذ 2020م عبر دورات تخصصية وخدمات متكاملة.</p>
                            <div class="contact-cards">
                                <a href="tel:+96892332749" class="contact-card">
                                    <div class="contact-icon phone"><i class="fas fa-phone-alt"></i></div>
                                    <div class="contact-details"><span class="label">اتصل بنا</span><span class="value" dir="ltr">+968 92332749</span></div>
                                </a>
                                <a href="mailto:info.expertplatform@gmail.com" class="contact-card">
                                    <div class="contact-icon email"><i class="fas fa-envelope"></i></div>
                                    <div class="contact-details"><span class="label">البريد الإلكتروني</span><span class="value">info.expertplatform@gmail.com</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-link"></i> روابط سريعة</h5>
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
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-cogs"></i> خدماتنا</h5>
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
                    <div class="col-lg-3">
                        <div class="footer-cta">
                            <h5 class="cta-title"><i class="fas fa-comments"></i> تواصل معنا</h5>
                            <p class="cta-desc">نسعد بالإجابة على استفساراتك</p>
                            <a href="https://wa.me/96892332749" class="whatsapp-btn" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i><span>تواصل عبر واتساب</span></a>
                            <div class="social-icons">
                                <span class="social-label">تابعنا على</span>
                                <div class="icons-row">
                                    <a href="https://instagram.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon instagram" title="انستقرام"><i class="fab fa-instagram"></i></a>
                                    <a href="https://linkedin.com/company/exp-edu" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" title="لينكدإن"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://youtube.com/@exp_edu" target="_blank" rel="noopener noreferrer" class="social-icon youtube" title="يوتيوب"><i class="fab fa-youtube"></i></a>
                                    <a href="https://x.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon twitter x-social" title="إكس" aria-label="إكس"><span class="x-social-mark">X</span></a>
                                </div>
                            </div>
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
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-content">
                    <div class="copyright"><span>© <?= date('Y') ?> بوابة خبرة</span><span class="separator">|</span><span>جميع الحقوق محفوظة</span></div>
                    <div class="bottom-links"><a href="#">سياسة الخصوصية</a><a href="#">الشروط والأحكام</a><a href="index.php#contact">تواصل معنا</a></div>
                    <div class="made-with"><span>صُنع بـ</span><i class="fas fa-heart"></i><span>في عُمان</span></div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#!') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>
