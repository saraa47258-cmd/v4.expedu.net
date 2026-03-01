<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="خدمات بوابة خبرة  - دورات وخدمات  في جميع المجالات">
    <title>خدماتنا - بوابة خبرة </title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Navbar CSS -->
    <link href="assets/css/navbar.css" rel="stylesheet">
    <!-- Footer CSS -->
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #066755;
            --primary-light: #0a8a6b;
            --primary-dark: #044a3d;
            --accent: #ffd700;
            --accent-hover: #ffaa00;
            --dark: #0a1628;
            --gray-100: #f8f9fa;
            --white: #ffffff;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }
        
        body {
            background: var(--gray-100);
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-services {
            position: relative;
            padding: 180px 0 120px;
            background: linear-gradient(160deg, var(--primary) 0%, var(--primary-dark) 50%, #022b23 100%);
            overflow: hidden;
        }
        
        .hero-services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,215,0,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }
        
        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,215,0,0.15);
            border: 1px solid rgba(255,215,0,0.3);
            padding: 10px 24px;
            border-radius: 50px;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 25px;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 20px;
        }
        
        .hero-title span {
            color: var(--accent);
        }
        
        .hero-desc {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.8);
            max-width: 700px;
            margin: 0 auto 40px;
        }
        
        .hero-features {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        
        .hero-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            font-weight: 600;
        }
        
        .hero-feature i {
            width: 40px;
            height: 40px;
            background: rgba(255,215,0,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }
        
        /* Services Section */
        .services-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #f0f7f5 0%, var(--white) 100%);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6,103,85,0.1);
            padding: 10px 24px;
            border-radius: 50px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--dark);
        }
        
        .section-title span {
            color: var(--primary);
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 15px auto 0;
        }
        
        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .service-card {
            background: var(--white);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            transition: all 0.4s ease;
            border: 1px solid rgba(6,103,85,0.08);
            position: relative;
        }
        
        .service-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 60px rgba(6,103,85,0.15);
            border-color: var(--primary);
        }
        
        .card-header-section {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-section.it { background: linear-gradient(135deg, #066755, #0a8a6b); }
        .card-header-section.chemical { background: linear-gradient(135deg, #7b2cbf, #9d4edd); }
        .card-header-section.construction { background: linear-gradient(135deg, #e65100, #ff8f00); }
        .card-header-section.english { background: linear-gradient(135deg, #0066cc, #00aaff); }
        .card-header-section.design { background: linear-gradient(135deg, #d63384, #f06595); }
        .card-header-section.accounting { background: linear-gradient(135deg, #20c997, #40e0d0); }
        
        .card-header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        
        .card-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            color: var(--white);
            position: relative;
            z-index: 2;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.4s;
        }
        
        .service-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .card-content {
            padding: 35px;
            text-align: center;
        }
        
        .card-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .card-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 25px;
        }
        
        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .feature-tag {
            background: rgba(6,103,85,0.08);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .btn-explore {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: var(--white) !important;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(6,103,85,0.25);
        }
        
        .btn-explore:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(6,103,85,0.35);
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 50%, rgba(255,215,0,0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 50%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            position: relative;
            z-index: 2;
        }
        
        .stat-item {
            text-align: center;
            padding: 30px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s;
        }
        
        .stat-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
            color: var(--primary-dark);
        }
        
        .stat-number {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
        }
        
        .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            margin-top: 8px;
        }
        
        /* Why Choose Us */
        .why-section {
            padding: 100px 0;
            background: var(--white);
        }
        
        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .why-card {
            background: linear-gradient(135deg, #f8fdfb, #ffffff);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(6,103,85,0.1);
            transition: all 0.3s;
        }
        
        .why-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(6,103,85,0.1);
            border-color: var(--primary);
        }
        
        .why-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            color: var(--white);
            box-shadow: 0 10px 30px rgba(6,103,85,0.25);
        }
        
        .why-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .why-desc {
            color: #666;
            line-height: 1.8;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(180deg, var(--white) 0%, #f0f7f5 100%);
        }
        
        .cta-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 40px;
            padding: 80px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,215,0,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.5rem;
            color: var(--accent);
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 15px;
        }
        
        .cta-desc {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 35px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: var(--primary-dark) !important;
            padding: 18px 40px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(255,215,0,0.3);
        }
        
        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255,215,0,0.4);
        }
        
        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            color: var(--white) !important;
            padding: 16px 38px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-3px);
        }
        
        /* Footer */
        .footer-mini {
            background: var(--dark);
            padding: 30px 0;
            text-align: center;
        }
        
        .footer-mini p {
            color: rgba(255,255,255,0.6);
            margin: 0;
        }
        
        .footer-mini a {
            color: var(--accent);
            text-decoration: none;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-features {
                gap: 20px;
            }
            
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .why-grid {
                grid-template-columns: 1fr;
            }
            

        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            
            .cta-card {
                padding: 50px 25px;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 374.98px) {
            .hero-title {
                font-size: 1.6rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-features {
                gap: 10px;
            }
            
            .hero-features span {
                font-size: 0.85rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-item {
                padding: 20px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .service-card {
                padding: 24px;
            }
            
            .why-card {
                padding: 24px;
            }
            
            .cta-card {
                padding: 40px 16px;
                border-radius: 24px;
            }
            
            .cta-title {
                font-size: 1.4rem;
            }
            
            .cta-desc {
                font-size: 1rem;
            }

            .btn-cta-primary,
            .btn-cta-secondary {
                padding: 14px 24px;
                font-size: 0.95rem;
                width: 100%;
                justify-content: center;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <?php $currentPage = 'services'; include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-services">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-rocket"></i>
                    +13 مجال تخصصي
                </div>
                <h1 class="hero-title">
                    <span>خدماتنا</span> المتميزة
                </h1>
                <p class="hero-desc">
                    نقدم لك مجموعة متكاملة من الخدمات  والتدريبية المصممة خصيصاً لتطوير مهاراتك وتحقيق أهدافك المهنية
                </p>
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>دورات معتمدة</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-users"></i>
                        <span>خبراء متخصصون</span>
                    </div>
                    <div class="hero-feature">
                        <i class="fas fa-award"></i>
                        <span>شهادات موثقة</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-th-large"></i>
                    مجالات متنوعة
                </div>
                <h2 class="section-title">استكشف <span>مجالاتنا</span> التخصصية</h2>
                <p class="section-subtitle">اختر المجال الذي يناسب اهتماماتك وابدأ رحلة التعلم</p>
            </div>
            
            <div class="services-grid">
                <!-- هندسة الاتصالات وتقنية المعلومات -->
                <div class="service-card">
                    <div class="card-header-section it">
                        <div class="card-icon">
                            <i class="fas fa-network-wired"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">هندسة الاتصالات وتقنية المعلومات</h3>
                        <p class="card-description">
                            دورات متخصصة في الشبكات والبرمجة وأمن المعلومات وتقنيات الاتصالات الحديثة
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">الشبكات</span>
                            <span class="feature-tag">البرمجة</span>
                            <span class="feature-tag">أمن المعلومات</span>
                        </div>
                        <a href="services/it.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <!-- الهندسة الكيميائية -->
                <div class="service-card">
                    <div class="card-header-section chemical">
                        <div class="card-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">الهندسة الكيميائية والبتروكيماويات</h3>
                        <p class="card-description">
                            تخصصات في العمليات الكيميائية والصناعات البترولية وهندسة المواد
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">البتروكيماويات</span>
                            <span class="feature-tag">العمليات</span>
                        </div>
                        <a href="services/chemical.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <!-- الإنشاءات -->
                <div class="service-card">
                    <div class="card-header-section construction">
                        <div class="card-icon">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">مجال الإنشاءات</h3>
                        <p class="card-description">
                            دورات في التصميم الإنشائي وإدارة المشاريع والرسم الهندسي والمساحة
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">التصميم</span>
                            <span class="feature-tag">إدارة المشاريع</span>
                        </div>
                        <a href="services/construction.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <!-- اللغة الانجليزية -->
                <div class="service-card">
                    <div class="card-header-section english">
                        <div class="card-icon">
                            <i class="fas fa-language"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">مجال اللغة الانجليزية</h3>
                        <p class="card-description">
                            دورات متكاملة في اللغة الإنجليزية للمبتدئين والمتقدمين مع التركيز على المحادثة
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">المحادثة</span>
                            <span class="feature-tag">IELTS</span>
                            <span class="feature-tag">TOEFL</span>
                        </div>
                        <a href="services/english.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <!-- التصميم -->
                <div class="service-card">
                    <div class="card-header-section design">
                        <div class="card-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">مجال التصميم</h3>
                        <p class="card-description">
                            دورات احترافية في التصميم الجرافيكي وتصميم الويب والموشن جرافيك
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">جرافيك</span>
                            <span class="feature-tag">UI/UX</span>
                            <span class="feature-tag">موشن</span>
                        </div>
                        <a href="services/design.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                <!-- إدارة الحسابات والتسويق -->
                <div class="service-card">
                    <div class="card-header-section accounting">
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">إدارة الحسابات والتسويق</h3>
                        <p class="card-description">
                            دورات في المحاسبة والإدارة المالية والتسويق الرقمي وإدارة الأعمال
                        </p>
                        <div class="card-features">
                            <span class="feature-tag">المحاسبة</span>
                            <span class="feature-tag">التسويق</span>
                        </div>
                        <a href="services/accounting.php" class="btn-explore">
                            <span>استكشف الخدمات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section class="why-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-star"></i>
                    مميزاتنا
                </div>
                <h2 class="section-title">لماذا <span>تختارنا</span>؟</h2>
            </div>
            
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="why-title">شهادات معتمدة</h3>
                    <p class="why-desc">
                        نقدم شهادات معتمدة ومعترف بها تعزز سيرتك الذاتية وتفتح لك أبواب الفرص المهنية
                    </p>
                </div>
                
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="why-title">خبراء متخصصون</h3>
                    <p class="why-desc">
                        فريق من الخبراء والأكاديميين العمانيين ذوي الخبرة العملية والأكاديمية العالية
                    </p>
                </div>
                
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3 class="why-title">تعلم مرن</h3>
                    <p class="why-desc">
                        منصة  رقمية متطورة تتيح لك التعلم في أي وقت ومن أي مكان
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h2 class="cta-title">لم تجد ما تبحث عنه؟</h2>
                    <p class="cta-desc">
                        تواصل معنا وسنساعدك في إيجاد الخدمة المناسبة لاحتياجاتك
                    </p>
                    <div class="cta-buttons">
                        <a href="index.php#contact" class="btn-cta-primary">
                            <i class="fas fa-envelope"></i>
                            تواصل معنا
                        </a>
                        <a href="https://wa.me/96899999999" class="btn-cta-secondary">
                            <i class="fab fa-whatsapp"></i>
                            واتساب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
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
</body>
</html>
