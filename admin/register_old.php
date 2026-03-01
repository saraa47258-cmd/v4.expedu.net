<?php
/**
 * صفحة التسجيل الإدارية المخصصة
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

$pageTitle = $hasUsers ? 'إضافة مستخدم جديد' : 'إنشاء أول حساب إداري';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
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
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .register-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .register-header p {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .register-body {
            padding: 40px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        
        .input-icon .form-control {
            padding-right: 45px;
        }
        
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }
        
        .role-option {
            position: relative;
        }
        
        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .role-card {
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .role-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }
        
        .role-option input[type="radio"]:checked + .role-card {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        .role-card i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
        }
        
        .role-card .role-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .role-card .role-desc {
            font-size: 0.85rem;
            color: #718096;
            line-height: 1.4;
        }
        
        .role-option input[type="radio"]:checked + .role-card i {
            color: #667eea;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 14px 32px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c53030;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert ul {
            margin: 0;
            padding-right: 20px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: #764ba2;
        }
        
        .password-strength {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-weak { 
            width: 33%; 
            background: #fc8181; 
        }
        
        .strength-medium { 
            width: 66%; 
            background: #f6ad55; 
        }
        
        .strength-strong { 
            width: 100%; 
            background: #68d391; 
        }
        
        @media (max-width: 768px) {
            .role-selector {
                grid-template-columns: 1fr;
            }
            
            .register-body {
                padding: 25px;
            }
            
            .register-header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Header -->
        <div class="register-header">
            <i class="fas fa-user-shield fa-3x mb-3"></i>
            <h1><?= $pageTitle ?></h1>
            <p>قم بإنشاء حساب جديد مع تحديد صلاحيات الوصول المناسبة</p>
        </div>

        <!-- Body -->
        <div class="register-body">
            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>يوجد أخطاء في النموذج:</strong>
                    <ul>
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong><?= htmlspecialchars($success) ?></strong>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="registerForm">
                <div class="row">
                    <!-- الاسم الكامل -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-user text-primary ms-1"></i>
                            الاسم الكامل <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   placeholder="أدخل الاسم الكامل"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                   required>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope text-primary ms-1"></i>
                            البريد الإلكتروني <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <input type="email" 
                                   class="form-control" 
                                   name="email" 
                                   placeholder="example@email.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <!-- رقم الهاتف -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone text-primary ms-1"></i>
                            رقم الهاتف (اختياري)
                        </label>
                        <div class="input-icon">
                            <input type="tel" 
                                   class="form-control" 
                                   name="phone" 
                                   placeholder="+968 XX XXX XXXX"
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>

                    <!-- كلمة المرور -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock text-primary ms-1"></i>
                            كلمة المرور <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   id="password"
                                   placeholder="أدخل كلمة مرور قوية"
                                   required>
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                    </div>

                    <!-- تأكيد كلمة المرور -->
                    <div class="col-12 mb-4">
                        <label class="form-label">
                            <i class="fas fa-lock text-primary ms-1"></i>
                            تأكيد كلمة المرور <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <input type="password" 
                                   class="form-control" 
                                   name="confirm" 
                                   placeholder="أعد إدخال كلمة المرور"
                                   required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <!-- نوع الحساب -->
                    <div class="col-12 mb-4">
                        <label class="form-label mb-2">
                            <i class="fas fa-user-tag text-primary ms-1"></i>
                            نوع الحساب والصلاحيات <span class="text-danger">*</span>
                        </label>
                        <div class="role-selector">
                            <!-- مشرف -->
                            <div class="role-option">
                                <input type="radio" 
                                       name="role" 
                                       value="instructor" 
                                       id="role_instructor" 
                                       <?= (!isset($_POST['role']) || $_POST['role'] === 'instructor') ? 'checked' : '' ?>>
                                <label for="role_instructor" class="role-card">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <div class="role-title">مشرف / مدرب</div>
                                    <div class="role-desc">
                                        يمكنه رفع وإدارة الكورسات والدروس
                                    </div>
                                </label>
                            </div>

                            <!-- أدمن -->
                            <div class="role-option">
                                <input type="radio" 
                                       name="role" 
                                       value="admin" 
                                       id="role_admin"
                                       <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'checked' : '' ?>>
                                <label for="role_admin" class="role-card">
                                    <i class="fas fa-user-shield"></i>
                                    <div class="role-title">مدير النظام</div>
                                    <div class="role-desc">
                                        صلاحيات كاملة لإدارة كل شيء في المنصة
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر التسجيل -->
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus ms-2"></i>
                    إنشاء الحساب
                </button>
            </form>

            <!-- روابط إضافية -->
            <div class="back-link">
                <?php if ($hasUsers): ?>
                    <a href="dashboard.php">
                        <i class="fas fa-chart-line ms-1"></i>
                        العودة إلى لوحة التحكم
                    </a>
                    <span class="mx-2">|</span>
                    <a href="users.php">
                        <i class="fas fa-users ms-1"></i>
                        إدارة المستخدمين
                    </a>
                <?php else: ?>
                    <a href="login.php">
                        <i class="fas fa-sign-in-alt ms-1"></i>
                        لديك حساب؟ تسجيل الدخول
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Password Strength Indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            strengthBar.className = 'password-strength-bar';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Form Animation on Submit
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function() {
            const btn = form.querySelector('.btn-register');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin ms-2"></i> جاري الإنشاء...';
            btn.disabled = true;
        });
    </script>
</body>
</html>

