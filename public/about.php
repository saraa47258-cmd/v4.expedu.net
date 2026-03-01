<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="بوابة خبرة - منصة رقمية مرخصة تقدم خدمات  وتدريبية لكافة قطاعات المجتمع">
    <title>من نحن - بوابة خبرة </title>
    
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
            --accent-dark: #e6c200;
            --dark: #0a1628;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
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
        .hero-about {
            position: relative;
            min-height: 100vh;
            background: linear-gradient(160deg, var(--primary) 0%, var(--primary-dark) 50%, #022b23 100%);
            display: flex;
            align-items: center;
            overflow: hidden;
            padding-top: 80px;
        }
        
        .hero-about::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255,215,0,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255,255,255,0.05) 0%, transparent 50%);
            pointer-events: none;
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
            animation: fadeInUp 0.8s ease;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 20px;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }
        
        .hero-title span {
            color: var(--accent);
        }
        
        .hero-desc {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.8);
            max-width: 600px;
            line-height: 1.9;
            animation: fadeInUp 0.8s ease 0.4s backwards;
        }
        
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 50px;
            animation: fadeInUp 0.8s ease 0.6s backwards;
        }
        
        .hero-stat {
            text-align: center;
        }
        
        .hero-stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: var(--accent);
            line-height: 1;
        }
        
        .hero-stat-label {
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
            margin-top: 8px;
        }
        
        .hero-visual {
            position: relative;
            animation: fadeInRight 1s ease 0.5s backwards;
        }
        
        .hero-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 30px;
            padding: 50px;
            text-align: center;
        }
        
        .hero-logo {
            width: 180px;
            height: 180px;
            border-radius: 40px;
            margin-bottom: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .hero-card h3 {
            color: var(--white);
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .hero-card p {
            color: rgba(255,255,255,0.7);
            font-size: 1.1rem;
        }
        
        /* CEO Section */
        .ceo-section {
            padding: 120px 0;
            background: var(--white);
            position: relative;
        }
        
        .ceo-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(180deg, var(--primary-dark) 0%, transparent 100%);
            opacity: 0.05;
        }
        
        .ceo-card {
            background: var(--white);
            border-radius: 40px;
            box-shadow: 0 25px 80px rgba(6,103,85,0.1);
            overflow: hidden;
            position: relative;
        }
        
        .ceo-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 40px;
            text-align: center;
        }
        
        .ceo-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 30px;
            color: var(--white);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .ceo-header h2 {
            color: var(--white);
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
        }
        
        .ceo-body {
            padding: 60px;
            display: flex;
            gap: 50px;
            align-items: center;
        }
        
        .ceo-image-wrapper {
            flex-shrink: 0;
            text-align: center;
        }
        
        .ceo-avatar {
            width: 200px;
            height: 200px;
            border-radius: 30px;
            object-fit: cover;
            border: 4px solid var(--primary);
            box-shadow: 0 15px 40px rgba(6,103,85,0.2);
            margin-bottom: 20px;
        }
        
        .ceo-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .ceo-title {
            color: var(--primary);
            font-weight: 600;
        }
        
        .ceo-quote {
            flex: 1;
            position: relative;
        }
        
        .quote-icon {
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.15;
            position: absolute;
            top: -20px;
            right: 0;
        }
        
        .quote-text {
            font-size: 1.4rem;
            line-height: 2.2;
            color: #444;
            font-style: italic;
            position: relative;
            z-index: 1;
        }
        
        .quote-signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .signature-line {
            width: 50px;
            height: 3px;
            background: var(--primary);
        }
        
        .signature-name {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        /* About Info Section */
        .about-info {
            padding: 100px 0;
            background: linear-gradient(180deg, #f8fdfb 0%, var(--white) 100%);
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
        
        /* Info Cards Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
        
        .info-card-premium {
            background: var(--white);
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            border: 1px solid rgba(6,103,85,0.08);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .info-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(6,103,85,0.1), transparent);
            border-radius: 0 28px 0 100%;
        }
        
        .info-card-premium:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(6,103,85,0.15);
            border-color: var(--primary);
        }
        
        .info-card-premium.featured {
            grid-column: span 2;
            display: flex;
            gap: 50px;
            align-items: center;
        }
        
        .card-icon-box {
            width: 85px;
            height: 85px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: var(--white);
            margin-bottom: 25px;
            position: relative;
        }
        
        .card-icon-box.green {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 10px 30px rgba(6,103,85,0.3);
        }
        
        .card-icon-box.gold {
            background: linear-gradient(135deg, #ffd700, #ffaa00);
            box-shadow: 0 10px 30px rgba(255,215,0,0.3);
        }
        
        .card-icon-box.blue {
            background: linear-gradient(135deg, #0066cc, #0099ff);
            box-shadow: 0 10px 30px rgba(0,102,204,0.3);
        }
        
        .info-card-premium h3 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 18px;
        }
        
        .info-card-premium p {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.9;
            margin: 0;
        }
        
        .info-card-premium ul {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
        }
        
        .info-card-premium ul li {
            padding: 10px 0;
            color: #555;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .info-card-premium ul li i {
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        /* Stats Section */
        .stats-section-premium {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, #022b23 100%);
            position: relative;
            overflow: hidden;
        }
        
        .stats-section-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 50%, rgba(255,215,0,0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 50%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            position: relative;
            z-index: 2;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent), #ffaa00);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--primary-dark);
        }
        
        .stat-number {
            font-size: 3.2rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: var(--white);
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
        
        .cta-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta-title {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 20px;
        }
        
        .cta-desc {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            max-width: 600px;
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
            background: linear-gradient(135deg, var(--accent), #ffaa00);
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
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-stats {
                flex-wrap: wrap;
                gap: 25px;
            }
            
            .ceo-body {
                flex-direction: column;
                text-align: center;
            }
            
            .quote-icon {
                position: static;
                margin-bottom: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .info-card-premium.featured {
                grid-column: span 1;
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            

        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-stat-number {
                font-size: 2rem;
            }
            
            .ceo-body {
                padding: 30px;
            }
            
            .quote-text {
                font-size: 1.1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-title {
                font-size: 2rem;
            }
            
            .cta-card {
                padding: 50px 30px;
            }
        }
        
        @media (max-width: 374.98px) {
            .hero-title {
                font-size: 1.7rem;
            }
            
            .hero-stat-number {
                font-size: 1.6rem;
            }
            
            .hero-stats {
                gap: 15px;
            }
            
            .ceo-body {
                padding: 20px;
            }
            
            .quote-text {
                font-size: 1rem;
            }
            
            .cta-title {
                font-size: 1.6rem;
            }
            
            .cta-card {
                padding: 35px 20px;
            }
            
            .info-card-premium {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <?php $currentPage = 'about'; include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-about">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-star"></i>
                            منذ عام 2020م
                        </div>
                        <h1 class="hero-title">
                            من هي <span>بوابة خبرة</span>؟
                        </h1>
                        <p class="hero-desc">
                            منصة رقمية عُمانية رائدة ومرخصة من الحكومة، نقدم خدمات  وتدريبية متميزة لكافة قطاعات المجتمع، بإشراف فريق عماني متخصص بنسبة 100%.
                        </p>
                        <div class="hero-stats">
                            <div class="hero-stat">
                                <div class="hero-stat-number">+320</div>
                                <div class="hero-stat-label">خبير ومشرف</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-number">+13</div>
                                <div class="hero-stat-label">تخصص علمي</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-number">+2200</div>
                                <div class="hero-stat-label">طالب مستفيد</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-visual">
                        <div class="hero-card">
                            <img src="assets/icons/logo.png" alt="بوابة خبرة" class="hero-logo">
                            <h3>بوابة خبرة</h3>
                            <p>اصنع طريقك للنجاح</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CEO Section -->
    <section class="ceo-section">
        <div class="container">
            <div class="ceo-card">
                <div class="ceo-header">
                    <div class="ceo-header-badge">
                        <i class="fas fa-quote-right"></i>
                        رسالة ملهمة
                    </div>
                    <h2>كلمة الرئيس التنفيذي</h2>
                </div>
                <div class="ceo-body">
                    <div class="ceo-image-wrapper">
                        <img src="img/sanad photo.jpg" alt="م. سند العامري" class="ceo-avatar">
                        <h4 class="ceo-name">م. سند العامري</h4>
                        <p class="ceo-title">المؤسس والرئيس التنفيذي</p>
                    </div>
                    <div class="ceo-quote">
                        <i class="fas fa-quote-right quote-icon"></i>
                        <p class="quote-text">
                            عندمـــا نجعـــل مـــن التحديـــــات إشراقــــات للعمـــــــل والطمــــــــوح، حينها تـــــــولـــــــد الإنجـــــــازات العظيمـــة والمشـــــــــاريـــــــع العملاقـــة، نبنيها بصدق النــــوايـــــا وإصرار الحيــاة؛ هكذا ولـــدت منصة خبــــرة من رحم التحـــــديــــات؛ لتكــــون نــــــــوراً في سمـــاء عُمـــاننــــا الحبيبة.
                        </p>
                        <div class="quote-signature">
                            <div class="signature-line"></div>
                            <span class="signature-name">م. سند العامري</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Info Section -->
    <section class="about-info">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-lightbulb"></i>
                    تعرف علينا أكثر
                </div>
                <h2 class="section-title">رؤيتنا و<span>رسالتنا</span></h2>
            </div>
            
            <div class="info-grid">
                <!-- من نحن - Featured -->
                <div class="info-card-premium featured">
                    <div>
                        <div class="card-icon-box green">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div>
                        <h3>من نحن؟</h3>
                        <p>
                            بوابة خبرة منصة رقمية <strong>مرخصة من الحكومة</strong>، تقدم خدمات  وتدريبية لكافة قطاعات المجتمع، ويشرف عليها فريق عماني بنسبة <strong>100٪</strong>.
                        </p>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> أكثر من <strong>320 عضواً</strong> في الفريق الإداري والأكاديمي</li>
                            <li><i class="fas fa-check-circle"></i> أكثر من <strong>13 تخصصاً علمياً</strong>، تشمل الهندسة والتعليم والعلوم الإنسانية</li>
                            <li><i class="fas fa-check-circle"></i> <strong>+2200 طالب</strong> استفادوا من خدماتنا</li>
                        </ul>
                    </div>
                </div>
                
                <!-- رؤيتنا -->
                <div class="info-card-premium">
                    <div class="card-icon-box gold">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>رؤيتنا</h3>
                    <p>
                        أن نكون <strong>منصة الارتقاء بالمهارات التحولية المُفضَّلة</strong> في المنطقة، ونموذجاً يُحتذى في التعليم الرقمي المتميز.
                    </p>
                </div>
                
                <!-- رسالتنا -->
                <div class="info-card-premium">
                    <div class="card-icon-box blue">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>رسالتنا</h3>
                    <p>
                        الارتقاء بالمهارات من أجل المستقبل عبر برامج تخصصية تدمج <strong>الذكاء التقني</strong> و<strong>الابتكار التعليمي</strong> لتحقيق التنمية المستدامة.
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
                    <h2 class="cta-title">هل أنت مستعد للانضمام إلينا؟</h2>
                    <p class="cta-desc">
                        كن جزءاً من قصة نجاحنا وابدأ رحلتك نحو التميز والتطور المهني
                    </p>
                    <div class="cta-buttons">
                        <a href="join.php" class="btn-cta-primary">
                            <i class="fas fa-user-plus"></i>
                            انضم إلينا الآن
                        </a>
                        <a href="index.php#contact" class="btn-cta-secondary">
                            <i class="fas fa-envelope"></i>
                            تواصل معنا
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
