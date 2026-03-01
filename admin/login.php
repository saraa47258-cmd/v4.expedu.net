<?php
/**
 * صفحة تسجيل دخول الأدمن والمشرفين - احترافية
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require __DIR__ . '/../public/includes/security.php';

// إذا كان مسجل دخول، وجّه للوحة التحكم
$currentUser = current_user();
if ($currentUser) {
    if (in_array($currentUser['role'], ['admin', 'instructor'])) {
        header('Location: dashboard.php');
        exit;
    } else {
        // طالب يحاول الدخول لصفحة الأدمن
        header('Location: ../public/user_dashboard.php');
        exit;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من CSRF Token
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'خطأ أمني: يرجى تحديث الصفحة والمحاولة مرة أخرى';
    }
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Rate Limiting - الحد من المحاولات المتكررة
    $clientIP = getClientIP();
    $rateLimitKey = 'admin_login_' . $clientIP . '_' . md5($email);
    if (!$errors && !checkRateLimit($rateLimitKey, 3, 30)) {
        $errors[] = 'تم تجاوز عدد المحاولات المسموحة. يرجى الانتظار 30 دقيقة.';
    }
    
    if (!$errors && (!$email || !$password)) {
        $errors[] = 'أدخل البريد وكلمة المرور';
    }
    
    if (!$errors && !login($email, $password)) {
        recordAttempt($rateLimitKey); // تسجيل المحاولة الفاشلة
        $errors[] = 'بيانات الدخول غير صحيحة';
    } elseif (!$errors) {
        clearAttempts($rateLimitKey); // مسح المحاولات عند النجاح
    }
    
    if (!$errors) {
        $user = current_user();
        
        // تحقق من أن المستخدم أدمن أو مشرف
        if (!in_array($user['role'], ['admin', 'instructor'])) {
            logout();
            $errors[] = 'هذه الصفحة مخصصة للأدمن والمشرفين فقط';
        } else {
            // تمديد الجلسة إذا اختار تذكرني
            if ($remember) {
                ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
                session_set_cookie_params(30 * 24 * 60 * 60);
            }
            
            // حماية من Open Redirect
            $next = $_GET['next'] ?? 'dashboard.php';
            // التحقق من أن الرابط محلي وآمن
            if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+$/', $next) || strpos($next, '..') !== false) {
                $next = 'dashboard.php';
            }
            header('Location: ' . $next);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن - ExpEdu</title>
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
            overflow: hidden;
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
        
        .login-container {
            max-width: 480px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .login-card {
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
            margin-bottom: 40px;
        }
        
        .logo-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: white;
            margin-bottom: 25px;
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
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            color: #64748b;
            font-size: 1.15rem;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .badge-role {
            display: inline-block;
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 700;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
        }
        
        .form-label {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
        }
        
        .input-group-custom {
            position: relative;
            margin-bottom: 25px;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 16px 55px 16px 20px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #0077b6;
            box-shadow: 0 0 0 5px rgba(0, 119, 182, 0.1);
            background: white;
        }
        
        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus ~ .input-icon {
            color: #0077b6;
        }
        
        .password-toggle {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.3rem;
            padding: 5px;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #0077b6;
            transform: translateY(-50%) scale(1.1);
        }
        
        .form-check {
            margin: 25px 0;
            padding-right: 0;
        }
        
        .form-check-input {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            margin-left: 12px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #0077b6;
            border-color: #0077b6;
        }
        
        .form-check-label {
            color: #475569;
            cursor: pointer;
            font-weight: 600;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%);
            border: none;
            border-radius: 15px;
            padding: 18px;
            font-size: 1.25rem;
            font-weight: 900;
            color: white;
            width: 100%;
            margin-top: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 15px 40px rgba(0, 119, 182, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(0, 119, 182, 0.5);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 25px;
            padding: 18px 20px;
            font-weight: 600;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .divider {
            text-align: center;
            margin: 35px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }
        
        .divider span {
            background: white;
            padding: 0 20px;
            color: #94a3b8;
            position: relative;
            z-index: 1;
            font-weight: 700;
        }
        
        .footer-link {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 15px;
        }
        
        .footer-link a {
            color: #0077b6;
            font-weight: 700;
            text-decoration: none;
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }
        
        .footer-link a:hover {
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
        
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
            padding: 15px;
            background: #f0f9ff;
            border-radius: 12px;
            color: #0369a1;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .security-badge i {
            font-size: 1.3rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h1 class="login-title">تسجيل دخول الأدمن</h1>
                <p class="login-subtitle">لوحة التحكم الإدارية</p>
                <span class="badge-role">
                    <i class="fas fa-crown me-2"></i>
                    مخصص للإدارة فقط
                </span>
            </div>
            
            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php foreach($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <?= csrf_field() ?>
                <div class="input-group-custom">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="admin@example.com" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required 
                           autofocus>
                    <i class="fas fa-at input-icon"></i>
                </div>
                
                <div class="input-group-custom">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control" 
                           placeholder="أدخل كلمة المرور" 
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember">
                        <label class="form-check-label" for="remember">
                            تذكرني لمدة 30 يوم
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    تسجيل الدخول
                </button>
                
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>اتصال آمن ومشفر</span>
                </div>
            </form>
            
            <div class="divider">
                <span>أو</span>
            </div>
            
            <div class="footer-link">
                <p class="mb-0">
                    لا تملك صلاحيات؟
                    <a href="../public/user_login.php">
                        <i class="fas fa-user me-1"></i>
                        تسجيل دخول كمستخدم
                    </a>
                </p>
            </div>
        </div>
        
        <div class="back-link">
            <a href="../public/index.php">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للصفحة الرئيسية</span>
            </a>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

