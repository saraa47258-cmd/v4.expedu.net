<?php
/**
 * صفحة إدارة المستخدمين - للأدمن فقط
 * عرض، تعديل، وحذف المستخدمين
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من تسجيل الدخول وإعادة التوجيه للـ login الصحيح
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

require_admin(); // فقط الأدمن يستطيع الوصول

$pdo = getPDO();
$me = current_user();

$errors = [];
$success = '';

// التحقق من CSRF للعمليات POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'خطأ أمني: يرجى تحديث الصفحة والمحاولة مرة أخرى';
    }
}

// حذف مستخدم
if (!$errors && isset($_POST['delete_user'])) {
    $userId = (int)$_POST['user_id'];
    
    // لا يمكن حذف نفسك
    if ($userId === $me['id']) {
        $errors[] = 'لا يمكنك حذف حسابك الخاص';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $success = 'تم حذف المستخدم بنجاح';
        } catch (PDOException $e) {
            error_log('Delete User Error: ' . $e->getMessage());
            $errors[] = 'حدث خطأ أثناء حذف المستخدم. يرجى المحاولة لاحقاً.';
        }
    }
}

// تغيير صلاحية مستخدم
if (!$errors && isset($_POST['change_role'])) {
    $userId = (int)$_POST['user_id'];
    $newRole = $_POST['new_role'];
    
    if (!in_array($newRole, ['admin', 'instructor', 'student'])) {
        $errors[] = 'نوع الصلاحية غير صالح';
    } elseif ($userId === $me['id']) {
        $errors[] = 'لا يمكنك تغيير صلاحيتك الخاصة';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$newRole, $userId]);
            $success = 'تم تحديث صلاحية المستخدم بنجاح';
        } catch (PDOException $e) {
            error_log('Change Role Error: ' . $e->getMessage());
            $errors[] = 'حدث خطأ أثناء تحديث الصلاحية. يرجى المحاولة لاحقاً.';
        }
    }
}

// جلب جميع المستخدمين
$users = $pdo->query("
    SELECT u.*, 
           COUNT(DISTINCT c.id) as courses_count,
           COUNT(DISTINCT l.id) as lessons_count
    FROM users u
    LEFT JOIN courses c ON c.instructor_id = u.id
    LEFT JOIN lessons l ON l.course_id = c.id
    GROUP BY u.id
    ORDER BY u.created_at DESC
")->fetchAll();

// إحصائيات
$stats = $pdo->query("
    SELECT 
        COUNT(CASE WHEN role = 'admin' THEN 1 END) as admin_count,
        COUNT(CASE WHEN role = 'instructor' THEN 1 END) as instructor_count,
        COUNT(CASE WHEN role = 'student' THEN 1 END) as student_count,
        COUNT(*) as total_count
    FROM users
")->fetch();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.95rem;
        }
        
        .users-table {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }
        
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .role-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .role-instructor {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .role-student {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        @media (max-width: 768px) {
            .table {
                font-size: 0.85rem;
            }
            
            .btn-action {
                padding: 4px 8px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-graduation-cap me-2"></i>
                ExpEdu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>لوحة التحكم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            <i class="fas fa-user-plus me-1"></i>إضافة مستخدم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../public/logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-users-cog text-primary me-2"></i>
                        إدارة المستخدمين
                    </h1>
                    <p class="text-muted mb-0">عرض وإدارة جميع مستخدمي المنصة</p>
                </div>
                <div>
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>
                        إضافة مستخدم جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    <?php foreach($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number text-primary"><?= $stats['total_count'] ?></div>
                    <div class="stat-label">إجمالي المستخدمين</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-number text-success"><?= $stats['admin_count'] ?></div>
                    <div class="stat-label">مدراء النظام</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number text-warning"><?= $stats['instructor_count'] ?></div>
                    <div class="stat-label">المشرفون</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-info">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-number text-info"><?= $stats['student_count'] ?></div>
                    <div class="stat-label">الطلاب</div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table">
            <h3 class="mb-4">
                <i class="fas fa-list me-2 text-primary"></i>
                قائمة المستخدمين
            </h3>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الصلاحية</th>
                            <th>الكورسات</th>
                            <th>الدروس</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                                        <?php if ($user['id'] === $me['id']): ?>
                                            <span class="badge bg-info ms-1">أنت</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="role-badge role-admin">
                                        <i class="fas fa-crown me-1"></i>مدير النظام
                                    </span>
                                <?php elseif ($user['role'] === 'instructor'): ?>
                                    <span class="role-badge role-instructor">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>مشرف
                                    </span>
                                <?php else: ?>
                                    <span class="role-badge role-student">
                                        <i class="fas fa-user-graduate me-1"></i>طالب
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $user['courses_count'] ?></span>
                            </td>
                            <td>
                                <span class="badge bg-success"><?= $user['lessons_count'] ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($user['id'] !== $me['id']): ?>
                                    <div class="btn-group">
                                        <!-- Change Role -->
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-action" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#roleModal<?= $user['id'] ?>">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        
                                        <!-- Delete User -->
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?= $user['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Role Change Modal -->
                                    <div class="modal fade" id="roleModal<?= $user['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">تغيير صلاحية المستخدم</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <p>تغيير صلاحية: <strong><?= htmlspecialchars($user['name']) ?></strong></p>
                                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">الصلاحية الجديدة</label>
                                                            <select name="new_role" class="form-select" required>
                                                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                                                                    مدير النظام
                                                                </option>
                                                                <option value="instructor" <?= $user['role'] === 'instructor' ? 'selected' : '' ?>>
                                                                    مشرف
                                                                </option>
                                                                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>
                                                                    طالب
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <button type="submit" name="change_role" class="btn btn-primary">حفظ التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal<?= $user['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">تأكيد الحذف</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من حذف المستخدم:</p>
                                                        <p><strong><?= htmlspecialchars($user['name']) ?></strong></p>
                                                        <p class="text-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            سيتم حذف جميع الكورسات والدروس الخاصة بهذا المستخدم!
                                                        </p>
                                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <button type="submit" name="delete_user" class="btn btn-danger">حذف نهائياً</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

