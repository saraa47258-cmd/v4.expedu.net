<?php
/**
 * صفحة إدارة الإعلانات والعروض - للأدمن فقط
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

require_admin(); // فقط الأدمن

$me = current_user();
$pdo = getPDO();

$errors = [];
$success = '';

// إضافة إعلان جديد
if (isset($_POST['add_ad'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'اعرف المزيد');
    $button_link = trim($_POST['button_link'] ?? '#');
    $image_url = trim($_POST['image_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($title)) {
        $errors[] = 'عنوان الإعلان مطلوب';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO ads (title, description, button_text, button_link, image_url, display_order, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $description, $button_text, $button_link, $image_url, $display_order, $is_active]);
            $success = 'تم إضافة الإعلان بنجاح';
        } catch (PDOException $e) {
            $errors[] = 'حدث خطأ: ' . $e->getMessage();
        }
    }
}

// تحديث إعلان
if (isset($_POST['update_ad'])) {
    $id = (int)$_POST['ad_id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'اعرف المزيد');
    $button_link = trim($_POST['button_link'] ?? '#');
    $image_url = trim($_POST['image_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($title)) {
        $errors[] = 'عنوان الإعلان مطلوب';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE ads 
                SET title = ?, description = ?, button_text = ?, button_link = ?, 
                    image_url = ?, display_order = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $description, $button_text, $button_link, $image_url, $display_order, $is_active, $id]);
            $success = 'تم تحديث الإعلان بنجاح';
        } catch (PDOException $e) {
            $errors[] = 'حدث خطأ: ' . $e->getMessage();
        }
    }
}

// حذف إعلان
if (isset($_POST['delete_ad'])) {
    $id = (int)$_POST['ad_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM ads WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'تم حذف الإعلان بنجاح';
    } catch (PDOException $e) {
        $errors[] = 'حدث خطأ: ' . $e->getMessage();
    }
}

// تبديل الحالة (تفعيل/تعطيل)
if (isset($_POST['toggle_status'])) {
    $id = (int)$_POST['ad_id'];
    try {
        $stmt = $pdo->prepare("UPDATE ads SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'تم تحديث حالة الإعلان';
    } catch (PDOException $e) {
        $errors[] = 'حدث خطأ: ' . $e->getMessage();
    }
}

// جلب جميع الإعلانات
$ads = $pdo->query("SELECT * FROM ads ORDER BY display_order ASC, created_at DESC")->fetchAll();

// إحصائيات
$totalAds = count($ads);
$activeAds = count(array_filter($ads, function($ad) { return $ad['is_active']; }));
$inactiveAds = $totalAds - $activeAds;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الإعلانات - ExpEdu</title>
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
        
        /* Sidebar - نفس التصميم */
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
            border: none;
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
        .stat-card-danger::before { background: linear-gradient(90deg, var(--danger-color), #dc2626); }
        
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
        .stat-card-icon-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        
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
        
        /* Content Card */
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
        
        /* Ad Card */
        .ad-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .ad-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }
        
        .ad-card-image {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            object-fit: cover;
            background: linear-gradient(135deg, #f1f5f9, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .ad-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .ad-card-description {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        
        .ad-card-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }
        
        .ad-card-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-sm-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
        }
        
        /* Form */
        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .main-content {
                margin-right: 0;
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
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="sidebar-menu-link">
                    <i class="fas fa-chart-line"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>
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
            <li class="sidebar-menu-item">
                <a href="courses.php" class="sidebar-menu-link">
                    <i class="fas fa-book"></i>
                    <span>إدارة الكورسات</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="upload.php" class="sidebar-menu-link">
                    <i class="fas fa-upload"></i>
                    <span>رفع كورس</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="ads.php" class="sidebar-menu-link active">
                    <i class="fas fa-bullhorn"></i>
                    <span>إدارة الإعلانات</span>
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
                <h2>إدارة الإعلانات والعروض 📢</h2>
                <p>تحكم في محتوى قسم "عروضنا المميزة" في الصفحة الرئيسية</p>
            </div>
            <div class="top-actions">
                <button class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#addAdModal">
                    <i class="fas fa-plus"></i>
                    <span>إضافة إعلان جديد</span>
                </button>
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
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-card-value"><?= $totalAds ?></div>
                <div class="stat-card-label">إجمالي الإعلانات</div>
            </div>
            
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon stat-card-icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-value"><?= $activeAds ?></div>
                <div class="stat-card-label">إعلانات نشطة</div>
            </div>
            
            <div class="stat-card stat-card-danger">
                <div class="stat-card-icon stat-card-icon-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-card-value"><?= $inactiveAds ?></div>
                <div class="stat-card-label">إعلانات معطلة</div>
            </div>
        </div>

        <!-- Ads List -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title">
                    <i class="fas fa-list text-primary me-2"></i>
                    قائمة الإعلانات
                </h3>
            </div>

            <?php if (count($ads) > 0): ?>
                <div class="row">
                    <?php foreach($ads as $ad): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="ad-card">
                            <?php if ($ad['image_url']): ?>
                                <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>" class="ad-card-image">
                            <?php else: ?>
                                <div class="ad-card-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h4 class="ad-card-title"><?= htmlspecialchars($ad['title']) ?></h4>
                            <p class="ad-card-description"><?= htmlspecialchars($ad['description']) ?></p>
                            
                            <div class="ad-card-meta">
                                <span>
                                    <i class="fas fa-sort text-primary me-1"></i>
                                    ترتيب: <?= $ad['display_order'] ?>
                                </span>
                                <span>
                                    <?php if ($ad['is_active']): ?>
                                        <span class="badge-status badge-active">
                                            <i class="fas fa-check me-1"></i>نشط
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status badge-inactive">
                                            <i class="fas fa-times me-1"></i>معطل
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <div class="ad-card-actions">
                                <button class="btn btn-sm btn-info btn-sm-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editAdModal<?= $ad['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                    تعديل
                                </button>
                                
                                <form method="POST" style="flex: 1;" onsubmit="return confirm('هل تريد تبديل حالة هذا الإعلان؟')">
                                    <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
                                    <button type="submit" name="toggle_status" class="btn btn-sm btn-warning btn-sm-action" style="width: 100%;">
                                        <i class="fas fa-toggle-on"></i>
                                        <?= $ad['is_active'] ? 'تعطيل' : 'تفعيل' ?>
                                    </button>
                                </form>
                                
                                <form method="POST" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')">
                                    <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
                                    <button type="submit" name="delete_ad" class="btn btn-sm btn-danger btn-sm-action" style="width: 100%;">
                                        <i class="fas fa-trash"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editAdModal<?= $ad['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">تعديل الإعلان</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">عنوان الإعلان *</label>
                                                <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($ad['title']) ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">الوصف</label>
                                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($ad['description']) ?></textarea>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">نص الزر</label>
                                                    <input type="text" class="form-control" name="button_text" value="<?= htmlspecialchars($ad['button_text']) ?>">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">رابط الزر</label>
                                                    <input type="text" class="form-control" name="button_link" value="<?= htmlspecialchars($ad['button_link']) ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">رابط الصورة</label>
                                                <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($ad['image_url']) ?>">
                                                <small class="text-muted">مثال: assets/images/ad1.jpg</small>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">ترتيب العرض</label>
                                                    <input type="number" class="form-control" name="display_order" value="<?= $ad['display_order'] ?>">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">الحالة</label>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active_<?= $ad['id'] ?>" <?= $ad['is_active'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_active_<?= $ad['id'] ?>">
                                                            نشط
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" name="update_ad" class="btn btn-primary">حفظ التغييرات</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-bullhorn" style="font-size: 5rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h3 style="color: #1e293b;">لا توجد إعلانات بعد</h3>
                    <p style="color: #64748b;">ابدأ بإضافة أول إعلان</p>
                    <button class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#addAdModal" style="display: inline-flex; margin-top: 20px;">
                        <i class="fas fa-plus"></i>
                        <span>إضافة إعلان جديد</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Ad Modal -->
    <div class="modal fade" id="addAdModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة إعلان جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-heading text-primary me-1"></i>
                                عنوان الإعلان *
                            </label>
                            <input type="text" class="form-control" name="title" placeholder="مثال: عرض خاص" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-right text-primary me-1"></i>
                                الوصف
                            </label>
                            <textarea class="form-control" name="description" rows="3" placeholder="احصل على خصم 30% على جميع الدورات"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-mouse-pointer text-primary me-1"></i>
                                    نص الزر
                                </label>
                                <input type="text" class="form-control" name="button_text" value="اعرف المزيد" placeholder="اعرف المزيد">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-link text-primary me-1"></i>
                                    رابط الزر
                                </label>
                                <input type="text" class="form-control" name="button_link" placeholder="courses.php">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-image text-primary me-1"></i>
                                رابط الصورة
                            </label>
                            <input type="text" class="form-control" name="image_url" placeholder="assets/images/ad1.jpg">
                            <small class="text-muted">يمكنك رفع الصورة إلى مجلد assets/images/ ثم كتابة المسار هنا</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sort text-primary me-1"></i>
                                    ترتيب العرض
                                </label>
                                <input type="number" class="form-control" name="display_order" value="0" min="0">
                                <small class="text-muted">الأرقام الأصغر تظهر أولاً</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الحالة</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new" checked>
                                    <label class="form-check-label" for="is_active_new">
                                        نشط (سيظهر في الصفحة الرئيسية)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" name="add_ad" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة الإعلان
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

