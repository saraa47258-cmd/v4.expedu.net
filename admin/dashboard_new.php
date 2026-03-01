<?php
/**
 * لوحة تحكم الأدمن الشاملة - Dashboard المحدثة
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من صلاحيات الأدمن
require_admin();

$me = current_user();
$pdo = getPDO();

// إحصائيات شاملة
$stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM courses) as total_courses,
        (SELECT COUNT(*) FROM lessons) as total_lessons,
        (SELECT COUNT(*) FROM join_requests) as total_join_requests,
        (SELECT COUNT(*) FROM join_requests WHERE status = 'pending') as pending_requests,
        (SELECT COUNT(*) FROM fields) as total_fields,
        (SELECT COUNT(*) FROM team_members) as total_team,
        (SELECT COUNT(*) FROM testimonials) as total_testimonials,
        (SELECT COUNT(*) FROM join_requests WHERE DATE(created_at) = CURDATE()) as today_requests
")->fetch();

// أحدث طلبات الانضمام
$recentJoinRequests = $pdo->query("
    SELECT * FROM join_requests 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الشاملة - بوابة خبرة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        
        /* Navbar */
        .admin-navbar {
            background: linear-gradient(135deg, #066755 0%, #044a3d 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-right: 4px solid;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        /* Management Card */
        .management-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            text-align: center;
            height: 100%;
            cursor: pointer;
        }
        
        .management-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .management-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            color: white;
        }
        
        .management-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }
        
        /* Recent Activity */
        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .request-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.2s;
        }
        
        .request-item:last-child {
            border-bottom: none;
        }
        
        .request-item:hover {
            background: #f8f9fa;
        }
        
        .badge-type {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar admin-navbar mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_new.php">
                <i class="fas fa-tachometer-alt me-2"></i>
                لوحة التحكم - بوابة خبرة
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= htmlspecialchars($me['name']) ?>
                </span>
                <a href="../public/logout.php" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    خروج
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <!-- الإحصائيات الرئيسية -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="border-right-color: #066755;">
                    <div class="stat-icon" style="background: rgba(6, 103, 85, 0.1); color: #066755;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" style="color: #066755;"><?= $stats['total_users'] ?></div>
                    <div class="stat-label">إجمالي المستخدمين</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="border-right-color: #0077b6;">
                    <div class="stat-icon" style="background: rgba(0, 119, 182, 0.1); color: #0077b6;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-number" style="color: #0077b6;"><?= $stats['total_courses'] ?></div>
                    <div class="stat-label">الكورسات</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="border-right-color: #dc3545;">
                    <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-number" style="color: #dc3545;"><?= $stats['total_join_requests'] ?></div>
                    <div class="stat-label">طلبات الانضمام</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stats-card" style="border-right-color: #ffc107;">
                    <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number" style="color: #ffc107;"><?= $stats['pending_requests'] ?></div>
                    <div class="stat-label">قيد الانتظار</div>
                </div>
            </div>
        </div>
        
        <!-- عنوان إدارة المحتوى -->
        <div class="mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-cogs me-2"></i>
                إدارة المحتوى
            </h2>
            <p class="text-muted">تحكم كامل في جميع محتويات الموقع</p>
        </div>
        
        <!-- بطاقات إدارة المحتوى -->
        <div class="row g-4 mb-4">
            <!-- إدارة طلبات الانضمام -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='join_requests.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #066755, #00d4ff);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h5 class="management-title">طلبات الانضمام</h5>
                    <p class="text-muted mb-3">إدارة الخبراء والمشرفين</p>
                    <span class="badge bg-danger"><?= $stats['pending_requests'] ?> جديد</span>
                </div>
            </div>
            
            <!-- إدارة المجالات -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='manage_fields.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #0077b6, #00d4ff);">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <h5 class="management-title">إدارة المجالات</h5>
                    <p class="text-muted mb-3">المجالات الستة</p>
                    <span class="badge bg-info"><?= $stats['total_fields'] ?> مجال</span>
                </div>
            </div>
            
            <!-- إدارة الخدمات -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='manage_services.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h5 class="management-title">إدارة الخدمات</h5>
                    <p class="text-muted mb-3">جميع الخدمات التفصيلية</p>
                    <span class="badge bg-success">36 خدمة</span>
                </div>
            </div>
            
            <!-- إدارة الكورسات -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='courses.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5 class="management-title">إدارة الكورسات</h5>
                    <p class="text-muted mb-3">الدورات التدريبية</p>
                    <span class="badge bg-primary"><?= $stats['total_courses'] ?> كورس</span>
                </div>
            </div>
            
            <!-- إدارة الفريق -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='manage_team.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="management-title">إدارة الفريق</h5>
                    <p class="text-muted mb-3">أعضاء الفريق</p>
                    <span class="badge bg-warning"><?= $stats['total_team'] ?> عضو</span>
                </div>
            </div>
            
            <!-- إدارة آراء العملاء -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='manage_testimonials.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="management-title">آراء العملاء</h5>
                    <p class="text-muted mb-3">قصص النجاح</p>
                    <span class="badge bg-pink"><?= $stats['total_testimonials'] ?> رأي</span>
                </div>
            </div>
            
            <!-- إدارة المستخدمين -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='users.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h5 class="management-title">إدارة المستخدمين</h5>
                    <p class="text-muted mb-3">الأدمن والمشرفين</p>
                    <span class="badge bg-danger"><?= $stats['total_users'] ?> مستخدم</span>
                </div>
            </div>
            
            <!-- الإحصائيات -->
            <div class="col-lg-3 col-md-6">
                <div class="management-card" onclick="window.location.href='manage_statistics.php'">
                    <div class="management-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h5 class="management-title">الإحصائيات</h5>
                    <p class="text-muted mb-3">أرقام الموقع</p>
                    <span class="badge bg-primary">6 إحصائيات</span>
                </div>
            </div>
        </div>
        
        <!-- أحدث طلبات الانضمام -->
        <div class="row">
            <div class="col-12">
                <div class="activity-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-bell me-2"></i>
                            أحدث طلبات الانضمام
                        </h4>
                        <a href="join_requests.php" class="btn btn-sm btn-primary">
                            عرض الكل
                            <i class="fas fa-arrow-left me-1"></i>
                        </a>
                    </div>
                    
                    <?php if (empty($recentJoinRequests)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>لا توجد طلبات حتى الآن</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentJoinRequests as $request): ?>
                            <div class="request-item">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <?php
                                        $typeLabels = [
                                            'expert' => 'خبير',
                                            'supervisor' => 'مشرف',
                                            'institution' => 'مؤسسة',
                                            'learner' => 'متعلم'
                                        ];
                                        $typeColors = [
                                            'expert' => 'primary',
                                            'supervisor' => 'success',
                                            'institution' => 'danger',
                                            'learner' => 'info'
                                        ];
                                        ?>
                                        <span class="badge-type bg-<?= $typeColors[$request['request_type']] ?> me-2">
                                            <?= $typeLabels[$request['request_type']] ?>
                                        </span>
                                        <strong><?= htmlspecialchars($request['full_name']) ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>
                                            <?= htmlspecialchars($request['email']) ?>
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('Y-m-d H:i', strtotime($request['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="join_requests.php" class="btn btn-sm btn-outline-primary">
                                            عرض
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- روابط سريعة -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="activity-card">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-link me-2"></i>
                        روابط سريعة
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="../public/index.php" class="btn btn-outline-secondary w-100" target="_blank">
                                <i class="fas fa-home me-2"></i>
                                الصفحة الرئيسية
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="../public/about.php" class="btn btn-outline-secondary w-100" target="_blank">
                                <i class="fas fa-info-circle me-2"></i>
                                من نحن
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="../public/services.php" class="btn btn-outline-secondary w-100" target="_blank">
                                <i class="fas fa-cogs me-2"></i>
                                خدماتنا
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="../public/join.php" class="btn btn-outline-secondary w-100" target="_blank">
                                <i class="fas fa-user-plus me-2"></i>
                                انضم إلينا
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تحديث تلقائي للإحصائيات كل دقيقة
        setInterval(() => {
            location.reload();
        }, 60000);
    </script>
</body>
</html>


