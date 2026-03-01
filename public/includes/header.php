<?php
/**
 * Header مشترك للصفحات العامة
 */
if (!isset($pageTitle)) {
    $pageTitle = 'بوابة خبرة ';
}
if (!isset($pageDescription)) {
    $pageDescription = 'منصة  عمانية رائدة تقدم خدمات  متميزة';
}
$currentUser = current_user();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#066755">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Preconnect for faster loading -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/responsive-enhancements.css" rel="stylesheet">
    
    <?php if (isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
    
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif !important;
            -webkit-font-smoothing: antialiased;
        }
        
        .custom-navbar {
            background: linear-gradient(135deg, #066755 0%, #044a3d 100%) !important;
            box-shadow: 0 4px 30px rgba(6, 103, 85, 0.3) !important;
            padding: 15px 0 !important;
        }
        
        .navbar-logo {
            width: 45px;
            height: 45px;
        }
        
        .navbar-brand-text {
            color: white !important;
            font-weight: 700 !important;
            font-size: 1.3rem;
        }
        
        .enhanced-nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            font-weight: 600 !important;
            padding: 10px 16px !important;
            border-radius: 10px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .enhanced-nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-cta-btn, .enhanced-cta-btn {
            background: linear-gradient(135deg, #E74C60 0%, #EE6676 100%) !important;
            border: none !important;
            padding: 12px 28px !important;
            border-radius: 25px !important;
            font-weight: 700 !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(231, 76, 96, 0.3);
        }
        
        .navbar-cta-btn:hover, .enhanced-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 96, 0.5);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/icons/logo.png" alt="شعار بوابة خبرة " class="me-2 navbar-logo">
                <span class="navbar-brand-text">بوابة خبرة </span>
            </a>
            
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                            <i class="fas fa-home me-2"></i>الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link <?= basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : '' ?>" href="courses.php">
                            <i class="fas fa-cloud-download-alt me-2"></i>سحابة خبرة
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link" href="services.php">
                            <i class="fas fa-concierge-bell me-2"></i>خدماتنا
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link enhanced-nav-link <?= basename($_SERVER['PHP_SELF']) == 'join.php' ? 'active' : '' ?>" href="join.php">
                            <i class="fas fa-user-plus me-2"></i>انضم إلينا
                        </a>
                    </li>
                </ul>
                
                <?php if ($currentUser): ?>
                    <div class="dropdown">
                        <button class="btn navbar-cta-btn enhanced-cta-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>
                            <?= htmlspecialchars($currentUser['name']) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (in_array($currentUser['role'], ['admin', 'instructor'])): ?>
                                <li><a class="dropdown-item" href="../admin/dashboard.php">
                                    <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="user_dashboard.php">
                                    <i class="fas fa-user-circle me-2"></i>حسابي
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-2">
                        <a href="user_login.php" class="btn btn-outline-light">
                            <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                        </a>
                        <a href="user_register.php" class="btn navbar-cta-btn enhanced-cta-btn">
                            <i class="fas fa-user-plus me-2"></i>التسجيل
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>

