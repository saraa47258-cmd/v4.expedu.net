<?php
/**
 * صفحة تسجيل دخول المستخدمين (الطلاب)
 */
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/security.php';

// إذا كان مسجل دخول، وجّه للوحة التحكم المناسبة
$currentUser = current_user();
if ($currentUser) {
    if ($currentUser['role'] === 'admin') {
        header('Location: ../admin/dashboard.php');
        exit;
    } elseif ($currentUser['role'] === 'instructor') {
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        header('Location: user_dashboard.php');
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
    
    // Rate Limiting - الحد من المحاولات المتكررة
    $clientIP = getClientIP();
    $rateLimitKey = 'login_' . $clientIP . '_' . md5($email);
    if (!checkRateLimit($rateLimitKey, 5, 15)) {
        $errors[] = 'تم تجاوز عدد المحاولات المسموحة. يرجى الانتظار 15 دقيقة.';
    }
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
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
        // تمديد مدة الجلسة إذا اختار "تذكرني"
        if ($remember) {
            ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60); // 30 يوم
            session_set_cookie_params(30 * 24 * 60 * 60);
        }
        
        // توجيه حسب الدور
        $user = current_user();
        if ($user['role'] === 'admin' || $user['role'] === 'instructor') {
            header('Location: ../admin/dashboard.php');
        } else {
            // حماية من Open Redirect
            $next = $_GET['next'] ?? 'user_dashboard.php';
            if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+$/', $next) || strpos($next, '..') !== false) {
                $next = 'user_dashboard.php';
            }
            header('Location: ' . $next);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 20px;
        }
        
        .login-container {
            max-width: 450px;
            margin: 0 auto;
            width: 100%;
        }
        
        .login-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px 40px;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
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
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            color: #718096;
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .password-toggle {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .form-check-label {
            color: #4a5568;
            cursor: pointer;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            background: white;
            padding: 0 20px;
            color: #718096;
            position: relative;
            z-index: 1;
            font-weight: 600;
        }
        
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
        }
        
        .register-link a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .register-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 25px;
        }
        
        .admin-link {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .admin-link a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .admin-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 576px) {
            body {
                padding: 20px 10px;
            }
            
            .login-card {
                padding: 35px 25px;
                border-radius: 20px;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 2.5rem;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
            
            .login-subtitle {
                font-size: 1rem;
            }
            
            .logo-section {
                margin-bottom: 25px;
            }
        }
        
        @media (max-width: 374.98px) {
            body {
                padding: 15px 8px;
            }
            
            .login-card {
                padding: 25px 18px;
                border-radius: 16px;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .login-subtitle {
                font-size: 0.9rem;
            }
            
            .form-control {
                padding: 12px 14px;
                font-size: 0.95rem;
            }
            
            .admin-link {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="login-title">مرحباً بعودتك!</h1>
                <p class="login-subtitle">سجل دخولك للمتابعة</p>
            </div>
            
            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>خطأ:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" name="email" class="form-control" placeholder="example@domain.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
                </div>
                
                <div class="mb-3 position-relative">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            تذكرني
                        </label>
                    </div>
                    <a href="#" class="forgot-password">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    تسجيل الدخول
                </button>
            </form>
            
            <div class="divider">
                <span>جديد على المنصة؟</span>
            </div>
            
            <div class="register-link">
                <p class="mb-0">
                    ليس لديك حساب؟
                    <a href="user_register.php">
                        <i class="fas fa-user-plus me-1"></i>
                        إنشاء حساب جديد
                    </a>
                </p>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="text-white text-decoration-none">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للصفحة الرئيسية
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


