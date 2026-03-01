<?php
/**
 * لوحة تحكم المستخدمين (الطلاب)
 */
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

// التحقق من تسجيل الدخول
if (!current_user()) {
    header('Location: user_login.php');
    exit;
}

$user = current_user();
$pdo = getPDO();

// إحصائيات المستخدم
$stats = [
    'enrolled_courses' => 0,  // عدد الكورسات المسجل بها (يمكن إضافة جدول enrollments لاحقاً)
    'completed_lessons' => 0, // عدد الدروس المكتملة
    'certificates' => 0,      // عدد الشهادات
    'total_courses' => $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn()
];

// أحدث الكورسات المتاحة
$recentCourses = $pdo->query("
    SELECT c.*, u.name as instructor_name,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lessons_count
    FROM courses c
    LEFT JOIN users u ON u.id = c.instructor_id
    ORDER BY c.created_at DESC
    LIMIT 6
")->fetchAll();

$welcome = isset($_GET['welcome']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: #f7fafc;
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea !important;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .main-content {
            padding: 40px 0;
        }
        
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }
        
        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 20px;
        }
        
        .stat-icon.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-icon.success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        }
        
        .stat-icon.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-icon.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .course-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .course-thumbnail {
            height: 180px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
        
        .course-body {
            padding: 20px;
        }
        
        .course-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .course-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #718096;
        }
        
        .course-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-course {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-course:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .action-btn {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #2d3748;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: #667eea;
        }
        
        .action-btn i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .action-btn strong {
            display: block;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .welcome-card {
                padding: 30px 20px;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .welcome-subtitle {
                font-size: 1.05rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stat-card {
                padding: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 25px 0;
            }
            
            .welcome-title {
                font-size: 1.7rem;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            
            .action-btn {
                padding: 15px;
            }
            
            .action-btn i {
                font-size: 1.5rem;
            }
            
            .action-btn strong {
                font-size: 0.95rem;
            }
            
            .course-thumbnail {
                height: 150px;
                font-size: 2.5rem;
            }
            
            .course-body {
                padding: 15px;
            }
            
            .course-title {
                font-size: 1.1rem;
            }
            
            .user-menu {
                gap: 8px;
            }
        }
        
        @media (max-width: 374.98px) {
            .welcome-card {
                padding: 20px 15px;
            }
            
            .welcome-title {
                font-size: 1.4rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
            
            .section-title {
                font-size: 1.2rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .stat-icon {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>
                ExpEdu
            </a>
            <div class="user-menu">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 600; color: #2d3748;">
                        <?= htmlspecialchars($user['name']) ?>
                    </div>
                    <div style="font-size: 0.85rem; color: #718096;">
                        طالب
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <?php if ($welcome): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>مرحباً بك!</strong> تم إنشاء حسابك بنجاح. استكشف الكورسات وابدأ رحلتك !
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Welcome Card -->
            <div class="welcome-card">
                <h1 class="welcome-title">
                    <i class="fas fa-hand-sparkles me-2"></i>
                    مرحباً، <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>!
                </h1>
                <p class="welcome-subtitle mb-0">
                    استعد لاستكشاف عالم من المعرفة والتعلم. لديك <?= $stats['total_courses'] ?> كورس متاح للتعلم!
                </p>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="courses.php" class="action-btn">
                    <i class="fas fa-book"></i>
                    <strong>تصفح الكورسات</strong>
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-bookmark"></i>
                    <strong>المفضلة</strong>
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-certificate"></i>
                    <strong>شهاداتي</strong>
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-user-circle"></i>
                    <strong>الملف الشخصي</strong>
                </a>
            </div>

            <!-- Statistics -->
            <div class="row mb-5">
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stat-number"><?= $stats['enrolled_courses'] ?></div>
                        <div class="stat-label">الكورسات المسجلة</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?= $stats['completed_lessons'] ?></div>
                        <div class="stat-label">الدروس المكتملة</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="stat-number"><?= $stats['certificates'] ?></div>
                        <div class="stat-label">الشهادات</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-number"><?= $stats['total_courses'] ?></div>
                        <div class="stat-label">الكورسات المتاحة</div>
                    </div>
                </div>
            </div>

            <!-- Recent Courses -->
            <h2 class="section-title">
                <i class="fas fa-fire"></i>
                الكورسات الأحدث
            </h2>
            
            <div class="row">
                <?php if ($recentCourses): ?>
                    <?php foreach($recentCourses as $course): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card">
                                <div class="course-thumbnail">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="course-body">
                                    <h3 class="course-title"><?= htmlspecialchars($course['title']) ?></h3>
                                    <div class="course-meta">
                                        <span>
                                            <i class="fas fa-chalkboard-teacher"></i>
                                            <?= htmlspecialchars($course['instructor_name']) ?>
                                        </span>
                                        <span>
                                            <i class="fas fa-video"></i>
                                            <?= $course['lessons_count'] ?> درس
                                        </span>
                                    </div>
                                    <a href="course.php?id=<?= $course['id'] ?>" class="btn btn-course">
                                        <i class="fas fa-play me-2"></i>
                                        ابدأ التعلم
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            لا توجد كورسات متاحة حالياً. تابعنا للحصول على آخر التحديثات!
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="courses.php" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 15px 40px; font-weight: 700;">
                    <i class="fas fa-book me-2"></i>
                    عرض جميع الكورسات
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


