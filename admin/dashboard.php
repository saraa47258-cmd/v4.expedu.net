<?php
/**
 * لوحة تحكم الأدمن الاحترافية - Dashboard
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// حصر الوصول على الأدمن فقط بفحص موحّد
require_admin();

$me = current_user();
$pdo = getPDO();

// إحصائيات شاملة
$stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM users WHERE role = 'admin') as admin_count,
        (SELECT COUNT(*) FROM users WHERE role = 'instructor') as instructor_count,
        (SELECT COUNT(*) FROM users WHERE role = 'student') as student_count,
        (SELECT COUNT(*) FROM courses) as total_courses,
        (SELECT COUNT(*) FROM lessons) as total_lessons,
        (SELECT COUNT(*) FROM categories) as total_categories
")->fetch();

// أحدث المستخدمين
$recentUsers = $pdo->query("
    SELECT id, name, email, role, created_at 
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();

// أحدث الكورسات
$recentCourses = $pdo->query("
    SELECT c.*, u.name as instructor_name,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lessons_count
    FROM courses c
    LEFT JOIN users u ON u.id = c.instructor_id
    ORDER BY c.created_at DESC
    LIMIT 5
")->fetchAll();

// إحصائيات المشرفين (أكثر المشرفين نشاطاً)
$topInstructors = $pdo->query("
    SELECT u.name, u.email, COUNT(c.id) as courses_count
    FROM users u
    LEFT JOIN courses c ON c.instructor_id = u.id
    WHERE u.role = 'instructor'
    GROUP BY u.id
    ORDER BY courses_count DESC
    LIMIT 5
")->fetchAll();

// نشاط اليوم
$todayUsers = $pdo->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()")->fetch()['count'];
$todayCourses = $pdo->query("SELECT COUNT(*) as count FROM courses WHERE DATE(created_at) = CURDATE()")->fetch()['count'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأدمن - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding: 30px 20px;
            box-shadow: -5px 0 30px rgba(0,0,0,0.3);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }
        
        .sidebar-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.8rem;
            color: white;
        }
        
        .sidebar-title {
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }
        
        .sidebar-subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
        }
        
        .sidebar-user {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .sidebar-user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.5rem;
            color: white;
        }
        
        .sidebar-user-name {
            color: white;
            font-weight: 600;
            margin: 0;
        }
        
        .sidebar-user-role {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu-item {
            margin-bottom: 10px;
        }
        
        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu-link:hover,
        .sidebar-menu-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(-5px);
        }
        
        .sidebar-menu-link i {
            width: 30px;
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .main-content {
            margin-right: 280px;
            padding: 30px;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text h2 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
        }
        
        .welcome-text p {
            margin: 0;
            color: #64748b;
        }
        
        .top-actions {
            display: flex;
            gap: 15px;
        }
        
        .action-btn {
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .stat-card-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stat-card-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .stat-card-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .stat-card-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
        
        .stat-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .stat-card-label {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .stat-card-change {
            margin-top: 10px;
            font-size: 0.85rem;
        }
        
        .change-positive {
            color: var(--success-color);
        }
        
        .change-negative {
            color: var(--danger-color);
        }
        
        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .content-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .content-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        .content-card-action {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .content-card-action:hover {
            color: var(--secondary-color);
        }
        
        /* Tables */
        .custom-table {
            width: 100%;
        }
        
        .custom-table thead {
            background: #f8fafc;
        }
        
        .custom-table th {
            padding: 15px;
            font-weight: 600;
            color: #475569;
            border: none;
            font-size: 0.9rem;
        }
        
        .custom-table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .custom-table tbody tr {
            transition: all 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .role-admin { background: #dbeafe; color: #1e40af; }
        .role-instructor { background: #fef3c7; color: #92400e; }
        .role-student { background: #d1fae5; color: #065f46; }
        
        /* Charts */
        .chart-container {
            height: 300px;
            position: relative;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
            }
            
            .top-actions {
                width: 100%;
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="sidebar-title">ExpEdu</h1>
            <p class="sidebar-subtitle">منصة التعليم الإلكتروني</p>
        </div>
        
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h3 class="sidebar-user-name"><?= htmlspecialchars($me['name']) ?></h3>
            <p class="sidebar-user-role">مدير النظام</p>
        </div>
        
        <ul class="sidebar-menu">
            <!-- القسم الرئيسي -->
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="sidebar-menu-link active">
                    <i class="fas fa-chart-line"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>
            
            <!-- إدارة المستخدمين -->
            <li class="sidebar-menu-item" style="margin-top: 20px;">
                <small style="color: rgba(255,255,255,0.4); padding: 0 15px; font-size: 0.75rem;">إدارة المستخدمين</small>
            </li>
            <li class="sidebar-menu-item">
                <a href="users.php" class="sidebar-menu-link">
                    <i class="fas fa-users"></i>
                    <span>المستخدمين</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="register.php" class="sidebar-menu-link">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة مستخدم</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="join_requests.php" class="sidebar-menu-link">
                    <i class="fas fa-user-clock"></i>
                    <span>طلبات الانضمام</span>
                </a>
            </li>
            
            <!-- إدارة المحتوى -->
            <li class="sidebar-menu-item" style="margin-top: 20px;">
                <small style="color: rgba(255,255,255,0.4); padding: 0 15px; font-size: 0.75rem;">إدارة المحتوى</small>
            </li>
            <li class="sidebar-menu-item">
                <a href="courses.php" class="sidebar-menu-link">
                    <i class="fas fa-book"></i>
                    <span>الكورسات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="upload.php" class="sidebar-menu-link">
                    <i class="fas fa-upload"></i>
                    <span>رفع كورس جديد</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="ads.php" class="sidebar-menu-link">
                    <i class="fas fa-bullhorn"></i>
                    <span>الإعلانات</span>
                </a>
            </li>
            
            <!-- إدارة الموقع -->
            <li class="sidebar-menu-item" style="margin-top: 20px;">
                <small style="color: rgba(255,255,255,0.4); padding: 0 15px; font-size: 0.75rem;">إدارة الموقع</small>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_homepage.php" class="sidebar-menu-link">
                    <i class="fas fa-home"></i>
                    <span>الصفحة الرئيسية</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_about.php" class="sidebar-menu-link">
                    <i class="fas fa-info-circle"></i>
                    <span>من نحن</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_team.php" class="sidebar-menu-link">
                    <i class="fas fa-user-tie"></i>
                    <span>الفريق</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_services.php" class="sidebar-menu-link">
                    <i class="fas fa-concierge-bell"></i>
                    <span>الخدمات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_fields.php" class="sidebar-menu-link">
                    <i class="fas fa-layer-group"></i>
                    <span>المجالات/التخصصات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_statistics.php" class="sidebar-menu-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>الإحصائيات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_testimonials.php" class="sidebar-menu-link">
                    <i class="fas fa-quote-right"></i>
                    <span>آراء العملاء</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manage_faq.php" class="sidebar-menu-link">
                    <i class="fas fa-question-circle"></i>
                    <span>الأسئلة الشائعة</span>
                </a>
            </li>
            
            <!-- روابط سريعة -->
            <li class="sidebar-menu-item" style="margin-top: 20px;">
                <small style="color: rgba(255,255,255,0.4); padding: 0 15px; font-size: 0.75rem;">روابط سريعة</small>
            </li>
            <li class="sidebar-menu-item">
                <a href="../public/index.php" class="sidebar-menu-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>عرض الموقع</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../public/logout.php" class="sidebar-menu-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="welcome-text">
                <h2>مرحباً بعودتك، <?= htmlspecialchars($me['name']) ?>! 👋</h2>
                <p>إليك نظرة عامة على أداء المنصة اليوم</p>
            </div>
            <div class="top-actions">
                <a href="upload.php" class="action-btn action-btn-primary">
                    <i class="fas fa-upload"></i>
                    <span>رفع كورس</span>
                </a>
                <a href="register.php" class="action-btn action-btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>إضافة مستخدم</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon stat-card-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-value"><?= $stats['total_users'] ?></div>
                <div class="stat-card-label">إجمالي المستخدمين</div>
                <div class="stat-card-change change-positive">
                    <i class="fas fa-arrow-up"></i>
                    +<?= $todayUsers ?> اليوم
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon stat-card-success">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-card-value"><?= $stats['admin_count'] ?></div>
                <div class="stat-card-label">مدراء النظام</div>
                <div class="stat-card-change" style="color: #64748b;">
                    <i class="fas fa-shield-alt"></i>
                    كامل الصلاحيات
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon stat-card-warning">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-card-value"><?= $stats['instructor_count'] ?></div>
                <div class="stat-card-label">المشرفون</div>
                <div class="stat-card-change" style="color: #64748b;">
                    <i class="fas fa-book"></i>
                    <?= $stats['total_courses'] ?> كورس
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon stat-card-info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-card-value"><?= $stats['total_courses'] ?></div>
                <div class="stat-card-label">الكورسات النشطة</div>
                <div class="stat-card-change change-positive">
                    <i class="fas fa-arrow-up"></i>
                    +<?= $todayCourses ?> اليوم
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Recent Users -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            أحدث المستخدمين
                        </h3>
                        <a href="users.php" class="content-card-action">
                            عرض الكل <i class="fas fa-arrow-left ms-1"></i>
                        </a>
                    </div>
                    
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الصلاحية</th>
                                <th>تاريخ التسجيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentUsers as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-badge">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php
                                    $roleClass = 'role-' . $user['role'];
                                    $roleText = [
                                        'admin' => 'مدير',
                                        'instructor' => 'مشرف',
                                        'student' => 'طالب'
                                    ][$user['role']];
                                    ?>
                                    <span class="role-badge <?= $roleClass ?>">
                                        <?= $roleText ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                    </small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Courses -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            <i class="fas fa-book text-success me-2"></i>
                            أحدث الكورسات
                        </h3>
                        <a href="courses.php" class="content-card-action">
                            عرض الكل <i class="fas fa-arrow-left ms-1"></i>
                        </a>
                    </div>
                    
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>عنوان الكورس</th>
                                <th>المدرب</th>
                                <th>الدروس</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentCourses as $course): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($course['title']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($course['instructor_name']) ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= $course['lessons_count'] ?> درس
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($course['created_at'])) ?>
                                    </small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Top Instructors -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            <i class="fas fa-star text-warning me-2"></i>
                            أكثر المشرفين نشاطاً
                        </h3>
                    </div>
                    
                    <?php foreach($topInstructors as $index => $instructor): ?>
                    <div class="d-flex align-items-center mb-3 p-3" style="background: #f8fafc; border-radius: 12px;">
                        <div class="me-3">
                            <div class="user-avatar" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <?= strtoupper(substr($instructor['name'], 0, 1)) ?>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <strong style="color: #1e293b;"><?= htmlspecialchars($instructor['name']) ?></strong>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($instructor['email']) ?></small>
                        </div>
                        <div>
                            <span class="badge bg-success" style="font-size: 1rem;">
                                <?= $instructor['courses_count'] ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Quick Stats -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">
                            <i class="fas fa-chart-pie text-info me-2"></i>
                            إحصائيات سريعة
                        </h3>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">نسبة المدراء</span>
                            <strong><?= $stats['total_users'] > 0 ? round(($stats['admin_count'] / $stats['total_users']) * 100, 1) : 0 ?>%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: <?= $stats['total_users'] > 0 ? ($stats['admin_count'] / $stats['total_users']) * 100 : 0 ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">نسبة المشرفين</span>
                            <strong><?= $stats['total_users'] > 0 ? round(($stats['instructor_count'] / $stats['total_users']) * 100, 1) : 0 ?>%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: <?= $stats['total_users'] > 0 ? ($stats['instructor_count'] / $stats['total_users']) * 100 : 0 ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">نسبة الطلاب</span>
                            <strong><?= $stats['total_users'] > 0 ? round(($stats['student_count'] / $stats['total_users']) * 100, 1) : 0 ?>%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?= $stats['total_users'] > 0 ? ($stats['student_count'] / $stats['total_users']) * 100 : 0 ?>%;"></div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">إجمالي الدروس</span>
                        <h4 class="mb-0 text-primary"><?= $stats['total_lessons'] ?></h4>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">التصنيفات</span>
                        <h4 class="mb-0 text-info"><?= $stats['total_categories'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

