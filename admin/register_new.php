<?php
/**
 * صفحة التسجيل الإدارية المخصصة - تصميم احترافي
 * تدعم إنشاء حسابات: أدمن (كل الصلاحيات) ومشرف (رفع الكورسات)
 */

require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// للحماية: يمكن للأدمن فقط الوصول لهذه الصفحة
// إذا لم يكن هناك مستخدمين، يسمح بإنشاء أول حساب
$pdo = getPDO();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role IN ('admin', 'instructor')");
$hasUsers = $stmt->fetch()['count'] > 0;

if ($hasUsers) {
    // إذا كان هناك مستخدمين، يجب أن يكون الزائر أدمن
    $currentUser = current_user();
    if (!$currentUser || $currentUser['role'] !== 'admin') {
        header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $role = $_POST['role'] ?? 'instructor';
    $phone = trim($_POST['phone'] ?? '');
    
    // التحقق من المدخلات
    if (empty($name)) $errors[] = 'الرجاء إدخال الاسم الكامل';
    if (empty($email)) $errors[] = 'الرجاء إدخال البريد الإلكتروني';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد الإلكتروني غير صالح';
    if (strlen($password) < 6) $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    if ($password !== $confirm) $errors[] = 'كلمتا المرور غير متطابقتين';
    if (!in_array($role, ['admin', 'instructor'])) $errors[] = 'نوع الحساب غير صالح';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt->execute([$name, $email, $hashed, $role, $phone]);
            
            $success = 'تم إنشاء الحساب بنجاح!';
            
            // إذا كان هذا أول حساب، سجل الدخول تلقائياً
            if (!$hasUsers) {
                login($email, $password);
                header('Location: dashboard.php');
                exit;
            }
            
            // مسح الحقول بعد النجاح
            $_POST = [];
            
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'هذا البريد الإلكتروني مستخدم من قبل';
            } else {
                $errors[] = 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = $hasUsers ? 'إضافة مستخدم إداري' : 'إنشاء أول حساب إداري';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 119, 182, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 180, 216, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(72, 202, 228, 0.1) 0%, transparent 50%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .register-container {
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.5);
            padding: 50px 40px;
            animation: slideUp 0.6s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .logo-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 20px 50px rgba(0, 119, 182, 0.4);
            animation: float 3s ease-in-out infinite;
            position: relative;
        }
        
        .logo-icon::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            bottom: -5px;
            left: -5px;
            background: linear-gradient(135deg, #0077b6, #00b4d8);
            border-radius: 25px;
            z-index: -1;
            opacity: 0.3;
            filter: blur(20px);
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(2deg);
            }
        }
        
        h1 {
            font-size: 2.2rem;
            font-weight: 900;
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #64748b;
            font-size: 1.05rem;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .first-setup {
            background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .first-setup i {
            font-size: 3.5rem;
            color: #0077b6;
            margin-bottom: 15px;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .first-setup h3 {
            color: #075985;
            font-weight: 900;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .first-setup p {
            color: #0c4a6e;
            font-weight: 600;
            margin: 0;
        }
        
        .form-label {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #0077b6;
            box-shadow: 0 0 0 4px rgba(0, 119, 182, 0.1);
            background: white;
        }
        
        .role-select-label {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            display: block;
            font-size: 1.05rem;
        }
        
        .role-select-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .role-option {
            position: relative;
        }
        
        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .role-card {
            background: #f8fafc;
            border: 3px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .role-option input[type="radio"]:checked + .role-card {
            background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
            border-color: #0077b6;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 119, 182, 0.3);
        }
        
        .role-card i {
            font-size: 2.8rem;
            margin-bottom: 12px;
            display: block;
        }
        
        .role-card.admin i {
            color: #dc2626;
        }
        
        .role-card.instructor i {
            color: #059669;
        }
        
        .role-card strong {
            display: block;
            font-size: 1.15rem;
            margin-bottom: 5px;
            color: #1e293b;
            font-weight: 900;
        }
        
        .role-card small {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            border: none;
            border-radius: 15px;
            padding: 18px;
            font-size: 1.25rem;
            font-weight: 900;
            width: 100%;
            color: white;
            margin-top: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 15px 40px rgba(0, 119, 182, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(0, 119, 182, 0.5);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 25px;
            padding: 18px 20px;
            font-weight: 600;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 15px;
        }
        
        .footer-text a {
            color: #0077b6;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }
        
        .footer-text a:hover {
            color: #005f8a;
            text-decoration: underline;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .back-link a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        @media (max-width: 576px) {
            .register-card {
                padding: 35px 25px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .logo-icon {
                width: 75px;
                height: 75px;
                font-size: 2.5rem;
            }
            
            .role-select-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1><?= $pageTitle ?></h1>
                <p class="subtitle">منصة ExpEdu الإدارية</p>
            </div>
            
            <?php if (!$hasUsers): ?>
                <div class="first-setup">
                    <i class="fas fa-rocket"></i>
                    <h3>مرحباً بك!</h3>
                    <p>أنشئ أول حساب إداري للبدء في استخدام المنصة</p>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        الاسم الكامل
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control" 
                           placeholder="أدخل الاسم الكامل"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="admin@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-phone"></i>
                        رقم الهاتف (اختياري)
                    </label>
                    <input type="tel" 
                           name="phone" 
                           class="form-control" 
                           placeholder="05xxxxxxxx"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </label>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="6 أحرف على الأقل"
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        تأكيد كلمة المرور
                    </label>
                    <input type="password" 
                           name="confirm" 
                           class="form-control" 
                           placeholder="أعد إدخال كلمة المرور"
                           required>
                </div>
                
                <label class="role-select-label">
                    <i class="fas fa-user-tag me-2"></i>
                    نوع الحساب
                </label>
                <div class="role-select-group">
                    <label class="role-option">
                        <input type="radio" 
                               name="role" 
                               value="admin" 
                               <?= (($_POST['role'] ?? 'admin') === 'admin') ? 'checked' : '' ?>>
                        <div class="role-card admin">
                            <i class="fas fa-crown"></i>
                            <strong>مدير النظام</strong>
                            <small>كل الصلاحيات</small>
                        </div>
                    </label>
                    
                    <label class="role-option">
                        <input type="radio" 
                               name="role" 
                               value="instructor" 
                               <?= (($_POST['role'] ?? '') === 'instructor') ? 'checked' : '' ?>>
                        <div class="role-card instructor">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <strong>مشرف</strong>
                            <small>رفع الكورسات</small>
                        </div>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    إنشاء الحساب
                </button>
            </form>
            
            <?php if ($hasUsers): ?>
                <div class="footer-text">
                    <a href="dashboard.php">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للوحة التحكم
                    </a>
                    <span class="mx-2">|</span>
                    <a href="users.php">
                        <i class="fas fa-users me-2"></i>
                        إدارة المستخدمين
                    </a>
                </div>
            <?php else: ?>
                <div class="footer-text">
                    <a href="login.php">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        لديك حساب؟ تسجيل الدخول
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="back-link">
            <a href="../public/index.php">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للصفحة الرئيسية</span>
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

