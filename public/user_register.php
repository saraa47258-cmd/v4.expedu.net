<?php
/**
 * صفحة تسجيل المستخدمين (الطلاب)
 */
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

// إذا كان مسجل دخول، وجّه للوحة التحكم
if (current_user()) {
    header('Location: user_dashboard.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من CSRF Token
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'خطأ أمني: يرجى تحديث الصفحة والمحاولة مرة أخرى';
    }
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // التحقق من البيانات
    if (!$errors) {
        if (!$name) $errors[] = 'الاسم مطلوب';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد الإلكتروني غير صحيح';
        
        // تعزيز قوة كلمة المرور
        if (!$password || strlen($password) < 8) {
            $errors[] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'كلمة المرور يجب أن تحتوي على حرف صغير واحد على الأقل';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل';
        }
        
        if ($password !== $confirm_password) $errors[] = 'كلمتا المرور غير متطابقتين';
    }
    
    if (!$errors) {
        try {
            $pdo = getPDO();
            
            // تحقق من عدم وجود البريد
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                $errors[] = 'البريد الإلكتروني مسجل بالفعل';
            } else {
                // إنشاء الحساب كطالب
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash, role, created_at) VALUES (?, ?, ?, ?, 'student', NOW())");
                $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
                
                // تسجيل دخول تلقائي
                login($email, $password);
                header('Location: user_dashboard.php?welcome=1');
                exit;
            }
        } catch (PDOException $e) {
            // تسجيل الخطأ في log بدون عرضه للمستخدم
            error_log('Registration Error: ' . $e->getMessage());
            $errors[] = 'حدث خطأ أثناء التسجيل. يرجى المحاولة لاحقاً.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل - ExpEdu</title>
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
        
        .register-container {
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }
        
        .register-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
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
            margin-bottom: 30px;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .register-subtitle {
            color: #718096;
            margin-bottom: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #718096;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 12px 0 0 12px;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 25px 0;
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
            padding: 0 15px;
            color: #718096;
            position: relative;
            z-index: 1;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4a5568;
            font-size: 0.9rem;
        }
        
        .feature i {
            color: #667eea;
            font-size: 1.2rem;
        }
        
        @media (max-width: 576px) {
            body {
                padding: 20px 10px;
            }
            
            .register-card {
                padding: 30px 22px;
                border-radius: 20px;
            }
            
            .register-title {
                font-size: 1.7rem;
            }
            
            .features {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .logo-icon {
                width: 65px;
                height: 65px;
                font-size: 2rem;
            }
        }
        
        @media (max-width: 374.98px) {
            body {
                padding: 15px 8px;
            }
            
            .register-card {
                padding: 22px 16px;
                border-radius: 16px;
            }
            
            .register-title {
                font-size: 1.4rem;
            }
            
            .register-subtitle {
                font-size: 0.9rem;
            }
            
            .form-control {
                padding: 10px 14px;
                font-size: 0.95rem;
            }
            
            .logo-icon {
                width: 55px;
                height: 55px;
                font-size: 1.8rem;
            }
            
            .feature {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="register-title">إنشاء حساب جديد</h1>
                <p class="register-subtitle">انضم إلى منصة ExpEdu </p>
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
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-2"></i>
                        الاسم الكامل
                    </label>
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسمك الكامل" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-envelope me-2"></i>
                        البريد الإلكتروني
                    </label>
                    <input type="email" name="email" class="form-control" placeholder="example@domain.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-phone me-2"></i>
                        رقم الهاتف (اختياري)
                    </label>
                    <input type="tel" name="phone" class="form-control" placeholder="05xxxxxxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        كلمة المرور
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل (حرف كبير + صغير + رقم)" required minlength="8">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        تأكيد كلمة المرور
                    </label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="أعد إدخال كلمة المرور" required>
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>
                    إنشاء حساب
                </button>
            </form>
            
            <div class="divider">
                <span>أو</span>
            </div>
            
            <div class="login-link">
                <p class="mb-0">
                    لديك حساب بالفعل؟ 
                    <a href="user_login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        تسجيل الدخول
                    </a>
                </p>
            </div>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-check-circle"></i>
                    <span>كورسات مجانية</span>
                </div>
                <div class="feature">
                    <i class="fas fa-certificate"></i>
                    <span>شهادات معتمدة</span>
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <span>مجتمع نشط</span>
                </div>
                <div class="feature">
                    <i class="fas fa-headset"></i>
                    <span>دعم فني 24/7</span>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="text-white text-decoration-none">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


