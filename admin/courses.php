<?php
/**
 * صفحة إدارة الكورسات - للأدمن والمشرفين
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// يسمح للأدمن والمشرف بالدخول، أما الطلاب فيُمنعون
require_role(['admin', 'instructor']);

$me = current_user();
$pdo = getPDO();

$errors = [];
$success = '';

// حذف كورس
if (isset($_POST['delete_course'])) {
    $courseId = (int)$_POST['course_id'];
    
    // إذا كان أدمن، يمكنه حذف أي كورس
    if ($me['role'] === 'admin') {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
        $success = 'تم حذف الكورس بنجاح';
    } else {
        // المشرف يحذف كورساته فقط
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ? AND instructor_id = ?");
        $stmt->execute([$courseId, $me['id']]);
        if ($stmt->rowCount() > 0) {
            $success = 'تم حذف الكورس بنجاح';
        } else {
            $errors[] = 'لا يمكنك حذف هذا الكورس';
        }
    }
}

// تحديث حالة الكورس
if (isset($_POST['toggle_status'])) {
    $courseId = (int)$_POST['course_id'];
    $newStatus = $_POST['new_status'];
    
    if ($me['role'] === 'admin') {
        // يمكن إضافة حقل status في المستقبل
        $success = 'تم تحديث حالة الكورس';
    }
}

// جلب الكورسات
if ($me['role'] === 'admin') {
    // الأدمن يرى جميع الكورسات
    $courses = $pdo->query("
        SELECT c.*, u.name as instructor_name, u.email as instructor_email,
               (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lessons_count
        FROM courses c
        LEFT JOIN users u ON u.id = c.instructor_id
        ORDER BY c.created_at DESC
    ")->fetchAll();
} else {
    // المشرف يرى كورساته فقط
    $stmt = $pdo->prepare("
        SELECT c.*, u.name as instructor_name, u.email as instructor_email,
               (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lessons_count
        FROM courses c
        LEFT JOIN users u ON u.id = c.instructor_id
        WHERE c.instructor_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$me['id']]);
    $courses = $stmt->fetchAll();
}

// إحصائيات
$totalCourses = count($courses);
$totalLessons = array_sum(array_column($courses, 'lessons_count'));
$totalRevenue = array_sum(array_column($courses, 'price'));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الكورسات - ExpEdu</title>
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
        }
        
        .stat-card-primary::before { background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); }
        .stat-card-success::before { background: linear-gradient(90deg, var(--success-color), #059669); }
        .stat-card-warning::before { background: linear-gradient(90deg, var(--warning-color), #d97706); }
        
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
        
        .stat-card-icon-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stat-card-icon-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .stat-card-icon-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        
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
        
        /* Courses Grid */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .course-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .course-thumbnail {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .course-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .course-thumbnail-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255,255,255,0.5);
        }
        
        .course-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.95);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .course-content {
            padding: 25px;
        }
        
        .course-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .course-instructor {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .course-instructor i {
            color: var(--primary-color);
        }
        
        .course-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .course-meta-item i {
            color: var(--primary-color);
        }
        
        .course-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-color);
            margin-bottom: 20px;
        }
        
        .course-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-action {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .courses-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
            }
            
            .courses-grid {
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
                <?php if($me['role'] === 'admin'): ?>
                    <i class="fas fa-user-shield"></i>
                <?php else: ?>
                    <i class="fas fa-chalkboard-teacher"></i>
                <?php endif; ?>
            </div>
            <h3 class="sidebar-user-name"><?= htmlspecialchars($me['name']) ?></h3>
            <p class="sidebar-user-role">
                <?= $me['role'] === 'admin' ? 'مدير النظام' : 'مشرف' ?>
            </p>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="sidebar-menu-link">
                    <i class="fas fa-chart-line"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>
            <?php if($me['role'] === 'admin'): ?>
            <li class="sidebar-menu-item">
                <a href="users.php" class="sidebar-menu-link">
                    <i class="fas fa-users"></i>
                    <span>إدارة المستخدمين</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="register.php" class="sidebar-menu-link">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة مستخدم</span>
                </a>
            </li>
            <?php endif; ?>
            <li class="sidebar-menu-item">
                <a href="courses.php" class="sidebar-menu-link active">
                    <i class="fas fa-book"></i>
                    <span>إدارة الكورسات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="upload.php" class="sidebar-menu-link">
                    <i class="fas fa-upload"></i>
                    <span>رفع كورس جديد</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../public/courses.php" class="sidebar-menu-link">
                    <i class="fas fa-graduation-cap"></i>
                    <span>عرض الكورسات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../public/index.php" class="sidebar-menu-link">
                    <i class="fas fa-home"></i>
                    <span>الصفحة الرئيسية</span>
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
                <h2>إدارة الكورسات 📚</h2>
                <p>
                    <?php if($me['role'] === 'admin'): ?>
                        إدارة شاملة لجميع الكورسات في المنصة
                    <?php else: ?>
                        إدارة الكورسات الخاصة بك
                    <?php endif; ?>
                </p>
            </div>
            <div class="top-actions">
                <a href="upload.php" class="action-btn action-btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>رفع كورس جديد</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php foreach($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon stat-card-icon-primary">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-card-value"><?= $totalCourses ?></div>
                <div class="stat-card-label">إجمالي الكورسات</div>
            </div>
            
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon stat-card-icon-success">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stat-card-value"><?= $totalLessons ?></div>
                <div class="stat-card-label">إجمالي الدروس</div>
            </div>
            
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon stat-card-icon-warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-value"><?= number_format($totalRevenue, 0) ?></div>
                <div class="stat-card-label">إجمالي القيمة (ريال)</div>
            </div>
        </div>

        <!-- Courses Grid -->
        <?php if (count($courses) > 0): ?>
            <div class="courses-grid">
                <?php foreach($courses as $course): ?>
                <div class="course-card">
                    <div class="course-thumbnail">
                        <?php if ($course['thumbnail_url']): ?>
                            <img src="<?= htmlspecialchars($course['thumbnail_url']) ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                        <?php else: ?>
                            <div class="course-thumbnail-placeholder">
                                <i class="fas fa-book"></i>
                            </div>
                        <?php endif; ?>
                        <span class="course-badge">
                            <i class="fas fa-video me-1"></i>
                            <?= $course['lessons_count'] ?> درس
                        </span>
                    </div>
                    
                    <div class="course-content">
                        <h3 class="course-title"><?= htmlspecialchars($course['title']) ?></h3>
                        
                        <div class="course-instructor">
                            <i class="fas fa-user-tie"></i>
                            <span><?= htmlspecialchars($course['instructor_name']) ?></span>
                        </div>
                        
                        <div class="course-meta">
                            <div class="course-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><?= date('d/m/Y', strtotime($course['created_at'])) ?></span>
                            </div>
                            <div class="course-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>منذ <?= floor((time() - strtotime($course['created_at'])) / 86400) ?> يوم</span>
                            </div>
                        </div>
                        
                        <?php if ($course['short_description']): ?>
                            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 15px;">
                                <?= htmlspecialchars(substr($course['short_description'], 0, 100)) ?>...
                            </p>
                        <?php endif; ?>
                        
                        <div class="course-price">
                            <?= number_format($course['price'], 2) ?> ريال
                        </div>
                        
                        <div class="course-actions">
                            <a href="../public/course.php?id=<?= $course['id'] ?>" class="btn-action btn-edit" target="_blank">
                                <i class="fas fa-eye"></i>
                                <span>عرض الكورس</span>
                            </a>
                            <form method="POST" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الكورس؟ سيتم حذف جميع الدروس أيضاً!')">
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <button type="submit" name="delete_course" class="btn-action btn-delete" style="width: 100%;">
                                    <i class="fas fa-trash"></i>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5" style="background: white; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <i class="fas fa-book" style="font-size: 5rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: #1e293b;">لا توجد كورسات بعد</h3>
                <p style="color: #64748b;">ابدأ بإضافة أول كورس لك</p>
                <a href="upload.php" class="action-btn action-btn-primary" style="display: inline-flex; margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    <span>رفع كورس جديد</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

