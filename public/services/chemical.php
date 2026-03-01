<?php
/**
 * صفحة خدمات الهندسة الكيميائية والبتروكيماويات
 */

$fieldInfo = [
    'id' => 'chemical',
    'name' => 'الهندسة الكيميائية وهندسة البتروكيماويات والعمليات',
    'icon' => 'fa-flask',
    'description' => 'دورات تخصصية في الهندسة الكيميائية والبتروكيماويات والعمليات الصناعية',
    'color' => '#dc3545'
];

$services = [
    [
        'id' => 1,
        'name' => 'أساسيات الهندسة الكيميائية',
        'icon' => 'fa-atom',
        'description' => 'دورة شاملة في أساسيات الهندسة الكيميائية والعمليات الصناعية',
        'price' => '180',
        'duration' => '40 ساعة'
    ],
    [
        'id' => 2,
        'name' => 'هندسة البتروكيماويات',
        'icon' => 'fa-oil-can',
        'description' => 'تعلم عمليات التكرير والمعالجة في صناعة البتروكيماويات',
        'price' => '200',
        'duration' => '45 ساعة'
    ],
    [
        'id' => 3,
        'name' => 'معالجة الغاز الطبيعي',
        'icon' => 'fa-fire',
        'description' => 'دورة متخصصة في عمليات معالجة وتكرير الغاز الطبيعي',
        'price' => '220',
        'duration' => '35 ساعة'
    ],
    [
        'id' => 4,
        'name' => 'السلامة الكيميائية',
        'icon' => 'fa-shield-alt',
        'description' => 'معايير السلامة والوقاية في المصانع الكيميائية',
        'price' => '150',
        'duration' => '25 ساعة'
    ],
    [
        'id' => 5,
        'name' => 'مشاريع التخرج الهندسية',
        'icon' => 'fa-project-diagram',
        'description' => 'إشراف ومساعدة في مشاريع التخرج الهندسية',
        'price' => '100',
        'duration' => 'حسب المشروع'
    ],
    [
        'id' => 6,
        'name' => 'استشارة هندسية',
        'icon' => 'fa-lightbulb',
        'description' => 'جلسة استشارية مع مهندس متخصص',
        'price' => '60',
        'duration' => 'ساعة واحدة'
    ]
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $fieldInfo['description'] ?> - بوابة خبرة ">
    <title><?= $fieldInfo['name'] ?> - بوابة خبرة</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f8f9fa; padding-top: 80px; }
        
        .custom-navbar {
            background: linear-gradient(135deg, #066755 0%, #044a3d 100%);
            box-shadow: 0 4px 20px rgba(6, 103, 85, 0.3);
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-logo { width: 45px; height: 45px; margin-left: 15px; }
        
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-back:hover { background: white; color: #066755; }
        
        .hero-section {
            background: linear-gradient(135deg, <?= $fieldInfo['color'] ?> 0%, #044a3d 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 60px;
            border-radius: 0 0 50px 50px;
        }
        
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, <?= $fieldInfo['color'] ?>, #00d4ff);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        .btn-request {
            background: linear-gradient(135deg, <?= $fieldInfo['color'] ?>, #00d4ff);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-request:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../assets/icons/logo.png" alt="Logo" class="navbar-logo">
                بوابة خبرة 
            </a>
            <a href="../services.php" class="btn btn-back">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للخدمات
            </a>
        </div>
    </nav>
    
    <section class="hero-section">
        <div class="container text-center">
            <div style="font-size: 5rem; margin-bottom: 20px;">
                <i class="fas <?= $fieldInfo['icon'] ?>"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3"><?= $fieldInfo['name'] ?></h1>
            <p class="lead" style="font-size: 1.3rem; opacity: 0.95;">
                <?= $fieldInfo['description'] ?>
            </p>
        </div>
    </section>
    
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">الخدمات المتاحة</h2>
            <p class="lead text-muted">اختر الخدمة المناسبة لك</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas <?= $service['icon'] ?>"></i>
                    </div>
                    <h4 class="fw-bold mb-3"><?= $service['name'] ?></h4>
                    <p class="text-muted mb-3" style="line-height: 1.7;"><?= $service['description'] ?></p>
                    <div class="mb-3">
                        <span class="badge bg-success me-2" style="font-size: 0.95rem;">
                            <i class="fas fa-tag me-1"></i>
                            <?= $service['price'] ?> ر.ع
                        </span>
                        <span class="badge bg-secondary" style="font-size: 0.95rem;">
                            <i class="fas fa-clock me-1"></i>
                            <?= $service['duration'] ?>
                        </span>
                    </div>
                    <button class="btn btn-request" onclick="window.location.href='../index.php#contact'">
                        <i class="fas fa-paper-plane me-2"></i>
                        طلب الخدمة
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="container mb-5">
        <div class="text-center p-5 rounded-4" style="background: linear-gradient(135deg, rgba(6, 103, 85, 0.1), rgba(0, 212, 255, 0.1)); border: 3px solid #066755;">
            <h2 class="fw-bold mb-3" style="color: #066755;">هل تحتاج إلى خدمة مخصصة؟</h2>
            <p class="lead mb-4">تواصل معنا وسنصمم لك حلاً يناسب احتياجاتك</p>
            <a href="../index.php#contact" class="btn btn-lg" style="background: linear-gradient(135deg, #066755, #00d4ff); color: white; padding: 15px 40px; border-radius: 15px; font-weight: 700;">
                <i class="fas fa-envelope me-2"></i>
                تواصل معنا
            </a>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


