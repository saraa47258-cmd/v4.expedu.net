<?php
/**
 * Template لصفحة مجال - يمكن تكرار هذا الملف لكل مجال
 * 
 * نسخ هذا الملف:
 * - it.php (هندسة الاتصالات)
 * - chemical.php (الهندسة الكيميائية)
 * - construction.php (الإنشاءات)
 * - english.php (اللغة الانجليزية)
 * - design.php (التصميم)
 * - accounting.php (الحسابات والتسويق)
 */

// معلومات المجال (عدّل هذه حسب كل مجال)
$fieldInfo = [
    'id' => 'it', // معرف المجال
    'name' => 'هندسة الاتصالات وتقنية المعلومات',
    'icon' => 'fa-network-wired',
    'description' => 'خدمات  وتدريبية متخصصة في مجال تقنية المعلومات والاتصالات',
    'color' => '#0077b6' // لون مخصص للمجال
];

// الخدمات (عدّل هذه حسب كل مجال)
$services = [
    [
        'id' => 1,
        'name' => 'دورة برمجة المواقع',
        'icon' => 'fa-code',
        'description' => 'تعلم تطوير المواقع من الصفر باستخدام HTML, CSS, JavaScript, PHP',
        'price' => '150',
        'duration' => '40 ساعة',
        'target' => ['student', 'graduate', 'freelancer']
    ],
    [
        'id' => 2,
        'name' => 'دورة تطوير التطبيقات',
        'icon' => 'fa-mobile-alt',
        'description' => 'تعلم بناء تطبيقات الموبايل باستخدام Flutter أو React Native',
        'price' => '200',
        'duration' => '50 ساعة',
        'target' => ['student', 'graduate', 'freelancer']
    ],
    [
        'id' => 3,
        'name' => 'استشارة تقنية',
        'icon' => 'fa-lightbulb',
        'description' => 'استشارة فنية مع خبراء تقنيين لحل مشاكلك أو تطوير مشروعك',
        'price' => '50',
        'duration' => 'ساعة واحدة',
        'target' => ['all']
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
        
        .navbar-logo {
            width: 45px;
            height: 45px;
            margin-left: 15px;
        }
        
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../assets/icons/logo.png" alt="Logo" class="navbar-logo">
                بوابة خبرة 
            </a>
            <a href="../services.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للخدمات
            </a>
        </div>
    </nav>
    
    <!-- Hero -->
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
    
    <!-- Services Grid -->
    <section class="container mb-5">
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas <?= $service['icon'] ?>"></i>
                    </div>
                    <h4 class="fw-bold mb-3"><?= $service['name'] ?></h4>
                    <p class="text-muted mb-3"><?= $service['description'] ?></p>
                    <div class="mb-3">
                        <span class="badge bg-secondary">
                            <i class="fas fa-clock me-1"></i>
                            <?= $service['duration'] ?>
                        </span>
                        <span class="badge bg-success me-2">
                            <i class="fas fa-tag me-1"></i>
                            <?= $service['price'] ?> ر.ع
                        </span>
                    </div>
                    <button class="btn btn-request" data-bs-toggle="modal" data-bs-target="#requestModal<?= $service['id'] ?>">
                        <i class="fas fa-paper-plane me-2"></i>
                        طلب الخدمة
                    </button>
                </div>
            </div>
            
            <!-- Modal لطلب الخدمة -->
            <div class="modal fade" id="requestModal<?= $service['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="border-radius: 20px;">
                        <div class="modal-header" style="background: linear-gradient(135deg, <?= $fieldInfo['color'] ?>, #00d4ff); color: white; border: none;">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-clipboard-list me-2"></i>
                                طلب خدمة: <?= $service['name'] ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="POST">
                                <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">الاسم الكامل <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">هل أنت؟</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" value="student" id="student<?= $service['id'] ?>">
                                                <label class="form-check-label" for="student<?= $service['id'] ?>">طالب/خريج</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" value="jobseeker" id="jobseeker<?= $service['id'] ?>">
                                                <label class="form-check-label" for="jobseeker<?= $service['id'] ?>">موظف/باحث عن عمل</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" value="freelancer" id="freelancer<?= $service['id'] ?>">
                                                <label class="form-check-label" for="freelancer<?= $service['id'] ?>">صاحب عمل حر</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" value="institution" id="institution<?= $service['id'] ?>">
                                                <label class="form-check-label" for="institution<?= $service['id'] ?>">مؤسسة/شركة</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">الرسالة / تفاصيل الطلب</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="أخبرنا بتفاصيل احتياجك..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(135deg, <?= $fieldInfo['color'] ?>, #00d4ff); color: white; border-radius: 12px; font-weight: 700;">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    أرسل الطلب الآن
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- CTA -->
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


