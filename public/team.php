<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="تعرف على فريق بوابة خبرة - نخبة من الخبراء والأكاديميين العمانيين">
    <title>فريقنا - بوابة خبرة </title>
    
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
        .hero-team {
            position: relative;
            padding: 180px 0 120px;
            background: linear-gradient(160deg, var(--primary) 0%, var(--primary-dark) 50%, #022b23 100%);
            overflow: hidden;
        }
        
        .hero-team::before {
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
            margin: 0 auto;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 50px;
        }
        
        .hero-stat {
            text-align: center;
        }
        
        .hero-stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--accent);
            line-height: 1;
        }
        
        .hero-stat-label {
            color: rgba(255,255,255,0.7);
            font-size: 1.1rem;
            margin-top: 10px;
        }
        
        /* Team Section */
        .team-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #f0f7f5 0%, var(--white) 100%);
            position: relative;
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
        
        /* Leadership Section */
        .leadership-section {
            margin-bottom: 80px;
        }
        
        .leader-card {
            background: var(--white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(6,103,85,0.1);
            display: flex;
            gap: 50px;
            padding: 50px;
            align-items: center;
            position: relative;
            border: 1px solid rgba(6,103,85,0.1);
        }
        
        .leader-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: linear-gradient(135deg, rgba(6,103,85,0.05), transparent);
            border-radius: 0 30px 30px 0;
        }
        
        .leader-avatar-wrapper {
            flex-shrink: 0;
            position: relative;
        }
        
        .leader-avatar {
            width: 220px;
            height: 220px;
            border-radius: 30px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 15px 40px rgba(6,103,85,0.25);
        }
        
        .leader-avatar i {
            font-size: 6rem;
            color: rgba(255,255,255,0.3);
        }

        .leader-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: inherit;
            display: block;
        }
        
        .leader-crown {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(255,215,0,0.4);
        }
        
        .leader-crown i {
            font-size: 1.5rem;
            color: var(--primary-dark);
        }
        
        .leader-info {
            flex: 1;
            position: relative;
            z-index: 2;
        }
        
        .leader-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            padding: 8px 20px;
            border-radius: 30px;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .leader-name {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .leader-title {
            font-size: 1.3rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .leader-quote {
            font-size: 1.15rem;
            color: #555;
            line-height: 1.9;
            padding-right: 20px;
            border-right: 4px solid var(--primary);
            font-style: italic;
        }
        
        .leader-social {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        
        .leader-social a {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(6,103,85,0.1);
            color: var(--primary);
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .leader-social a:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
        }
        
        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .team-card-premium {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            transition: all 0.4s ease;
            border: 1px solid rgba(6,103,85,0.08);
            position: relative;
        }
        
        .team-card-premium:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 60px rgba(6,103,85,0.15);
            border-color: var(--primary);
        }
        
        .card-image-section {
            height: 200px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-image-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }
        
        .card-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--white);
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        
        .card-avatar i {
            font-size: 3.5rem;
            color: rgba(255,255,255,0.7);
        }
        
        .card-role-badge {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.95);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 3;
        }
        
        .card-content {
            padding: 30px;
            text-align: center;
        }
        
        .card-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .card-position {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .card-specialties {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }
        
        .specialty-tag {
            background: rgba(6,103,85,0.08);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        /* Stats Banner */
        .stats-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 60px 0;
            margin: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .stats-banner::before {
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
            padding: 20px;
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
        
        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: var(--primary-dark) !important;
            padding: 18px 45px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.15rem;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(255,215,0,0.3);
        }
        
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255,215,0,0.4);
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
            
            .hero-stats {
                flex-wrap: wrap;
                gap: 30px;
            }
            
            .leader-card {
                flex-direction: column;
                text-align: center;
                padding: 40px 30px;
            }
            
            .leader-quote {
                border-right: none;
                border-top: 4px solid var(--primary);
                padding-right: 0;
                padding-top: 20px;
            }
            
            .leader-social {
                justify-content: center;
            }
            
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            

        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .team-grid {
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
            
            .hero-stats {
                flex-direction: column;
                gap: 16px;
                align-items: center;
            }
            
            .hero-stat-number {
                font-size: 1.8rem;
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
            
            .leader-card {
                padding: 24px 16px;
            }
            
            .member-card {
                padding: 20px;
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
    <?php $currentPage = 'team'; include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-team">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-users"></i>
                    فريق عماني 100%
                </div>
                <h1 class="hero-title">
                    <span>فريقنا</span> المتميز
                </h1>
                <p class="hero-desc">
                    نخبة من الخبراء والأكاديميين العمانيين المتخصصين في مختلف المجالات، نعمل معاً لتحقيق رؤية بوابة خبرة في بناء مستقبل تعليمي أفضل
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">+320</div>
                        <div class="hero-stat-label">عضو في الفريق</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">+13</div>
                        <div class="hero-stat-label">تخصص مختلف</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">100%</div>
                        <div class="hero-stat-label">فريق عماني</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <!-- Leadership Section -->
            <div class="leadership-section">
                <div class="section-header">
                    <div class="section-badge">
                        <i class="fas fa-crown"></i>
                        القيادة التنفيذية
                    </div>
                    <h2 class="section-title">المؤسس و<span>الرئيس التنفيذي</span></h2>
                </div>
                
                <div class="leader-card">
                    <div class="leader-avatar-wrapper">
                        <div class="leader-avatar">
                            <img src="img/sanad photo.jpg" alt="م. سند العامري">
                        </div>
                        <div class="leader-crown">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="leader-info">
                        <div class="leader-badge">
                            <i class="fas fa-star"></i>
                            المؤسس والرئيس التنفيذي
                        </div>
                        <h3 class="leader-name">م. سند العامري</h3>
                        <p class="leader-title">Founder & CEO</p>
                        <p class="leader-quote">
                            "عندمـا نجعـل مـن التحديـات إشراقـات للعمـل والطمـوح، حينها تـولـد الإنجـازات العظيمـة والمشـاريـع العملاقـة، نبنيها بصدق النـوايـا وإصرار الحيـاة."
                        </p>
                        <div class="leader-social">
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-x-twitter"></i></a>
                            <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Grid -->
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-user-tie"></i>
                    فريق الإدارة
                </div>
                <h2 class="section-title">فريق <span>القيادة</span> الإداري</h2>
            </div>
            
            <div class="team-grid">
                <!-- م. هيثم العامري -->
                <div class="team-card-premium">
                    <div class="card-image-section">
                        <div class="card-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="card-role-badge">المدير العام</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">م. هيثم العامري</h3>
                        <p class="card-position">المدير العام للمنصة</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">الإدارة</span>
                            <span class="specialty-tag">التخطيط</span>
                        </div>
                    </div>
                </div>
                
                <!-- م. أزهار الحراصية -->
                <div class="team-card-premium">
                    <div class="card-image-section">
                        <div class="card-avatar">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="card-role-badge">مدير الجامعة</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">م. أزهار الحراصية</h3>
                        <p class="card-position">مدير جامعة مواهب خبرة</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">التعليم</span>
                            <span class="specialty-tag">الأكاديمية</span>
                        </div>
                    </div>
                </div>
                
                <!-- أ. نعيمة الرواحية -->
                <div class="team-card-premium">
                    <div class="card-image-section">
                        <div class="card-avatar">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="card-role-badge">التسويق</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">أ. نعيمة الرواحية</h3>
                        <p class="card-position">مدير إدارة الهوية والتسويق</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">التسويق</span>
                            <span class="specialty-tag">العلامة التجارية</span>
                        </div>
                    </div>
                </div>
                
                <!-- م. صفية العمرية -->
                <div class="team-card-premium">
                    <div class="card-image-section">
                        <div class="card-avatar">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span class="card-role-badge">الدعم الفني</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">م. صفية العمرية</h3>
                        <p class="card-position">مدير إدارة الدعم الفني والمالي</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">الدعم الفني</span>
                            <span class="specialty-tag">المالية</span>
                        </div>
                    </div>
                </div>
                
                <!-- م. نوف الصالحية -->
                <div class="team-card-premium">
                    <div class="card-image-section">
                        <div class="card-avatar">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="card-role-badge">المناسبات</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">م. نوف الصالحية</h3>
                        <p class="card-position">مدير خدمات مناسبات التخصصات</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">الفعاليات</span>
                            <span class="specialty-tag">التنظيم</span>
                        </div>
                    </div>
                </div>
                
                <!-- عضو إضافي -->
                <div class="team-card-premium">
                    <div class="card-image-section" style="background: linear-gradient(135deg, #0066cc, #00aaff);">
                        <div class="card-avatar">
                            <i class="fas fa-code"></i>
                        </div>
                        <span class="card-role-badge">التقنية</span>
                    </div>
                    <div class="card-content">
                        <h3 class="card-name">الفريق التقني</h3>
                        <p class="card-position">فريق تطوير المنصة</p>
                        <div class="card-specialties">
                            <span class="specialty-tag">البرمجة</span>
                            <span class="specialty-tag">التصميم</span>
                        </div>
                    </div>
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
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="cta-title">انضم لفريقنا المتميز</h2>
                    <p class="cta-desc">
                        نبحث دائماً عن الكفاءات والمواهب المتميزة للانضمام إلى رحلتنا في بناء مستقبل تعليمي أفضل
                    </p>
                    <a href="join.php" class="btn-cta">
                        <i class="fas fa-rocket"></i>
                        انضم كخبير أو مشرف
                    </a>
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
