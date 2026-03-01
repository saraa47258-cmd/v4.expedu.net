<?php
/**
 * سكريبت تغيير كلمة مرور الأدمن مباشرة
 * 
 * الاستخدام:
 * 1. افتح هذا الملف من المتصفح: http://localhost/expedu/change_admin_password.php
 * 2. أدخل البريد الإلكتروني وكلمة المرور الجديدة
 * 3. احذف هذا الملف بعد الاستخدام لأسباب أمنية!
 */

// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// معالجة النموذج
$success = false;
$error = null;
$userName = '';
$email = '';
$userRole = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // التحقق من وجود ملف config
        if (!file_exists(__DIR__ . '/public/includes/config.php')) {
            throw new Exception('ملف config.php غير موجود. تأكد من أن المسار صحيح.');
        }
        
        $config = require __DIR__ . '/public/includes/config.php';
        
        $email = trim($_POST['email'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        
        // التحقق من الحقول
        if (empty($email) || empty($newPassword)) {
            throw new Exception('جميع الحقول مطلوبة');
        }
        
        // التحقق من صحة البريد الإلكتروني
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('البريد الإلكتروني غير صحيح');
        }
        
        if (strlen($newPassword) < 6) {
            throw new Exception('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
        }
        
        // الاتصال بقاعدة البيانات
        $db = $config->db;
        $dsn = "mysql:host={$db->host};dbname={$db->name};charset=utf8mb4";
        
        try {
            $pdo = new PDO($dsn, $db->user, $db->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (PDOException $e) {
            throw new Exception('خطأ في الاتصال بقاعدة البيانات: تأكد من تشغيل MySQL وصحة بيانات config.php');
        }
        
        // التحقق من وجود المستخدم
        $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception('البريد الإلكتروني غير موجود في قاعدة البيانات');
        }
        
        // توليد hash لكلمة المرور الجديدة
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // تحديث كلمة المرور
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$passwordHash, $email]);
        
        // التحقق من نجاح التحديث
        if ($stmt->rowCount() === 0) {
            throw new Exception('لم يتم تحديث أي صفوف. قد تكون كلمة المرور نفسها السابقة.');
        }
        
        $success = true;
        $userName = $user['name'];
        $userRole = $user['role'];
        
    } catch (PDOException $e) {
        $error = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير كلمة مرور الأدمن</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-right: 4px solid #ffc107;
            color: #856404;
            font-size: 14px;
        }
        .warning strong {
            display: block;
            margin-bottom: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .password-toggle {
            position: relative;
        }
        .toggle-btn {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
        }
        .success {
            background: #d4edda;
            border: 2px solid #28a745;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #155724;
        }
        .success h2 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .success-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #721c24;
            text-align: center;
        }
        .info-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-right: 4px solid #2196F3;
            font-size: 13px;
        }
        .delete-notice {
            background: #ffebee;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-right: 4px solid #f44336;
            color: #c62828;
            font-size: 13px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 تغيير كلمة المرور</h1>
        <p class="subtitle">نظام ExpEdu - إدارة المستخدمين</p>

        <?php if (!$success): ?>
            <div class="warning">
                <strong>⚠️ تحذير أمني:</strong>
                هذا السكريبت يجب استخدامه فقط للضرورة. احذفه فوراً بعد تغيير كلمة المرور!
            </div>

            <?php if ($error): ?>
                <div class="error">
                    <strong>❌ خطأ:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">البريد الإلكتروني:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           placeholder="admin@expedu.com"
                           value="admin@expedu.com">
                </div>

                <div class="form-group">
                    <label for="new_password">كلمة المرور الجديدة:</label>
                    <div class="password-toggle">
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               required 
                               minlength="6"
                               placeholder="أدخل كلمة المرور الجديدة (6 أحرف على الأقل)">
                        <button type="button" class="toggle-btn" onclick="togglePassword()">👁️</button>
                    </div>
                </div>

                <button type="submit">🔄 تغيير كلمة المرور</button>
            </form>

            <div class="info-box">
                <strong>💡 ملاحظة:</strong>
                سيتم تحديث كلمة المرور مباشرة في قاعدة البيانات. تأكد من حفظ كلمة المرور الجديدة في مكان آمن.
            </div>

        <?php else: ?>
            <div class="success">
                <div class="success-icon">✅</div>
                <h2>تم التحديث بنجاح!</h2>
                <p style="margin: 15px 0;">تم تغيير كلمة المرور بنجاح</p>
                <div style="background: white; padding: 15px; border-radius: 8px; margin: 15px 0; text-align: right;">
                    <p><strong>الاسم:</strong> <?= htmlspecialchars($userName) ?></p>
                    <p><strong>البريد:</strong> <?= htmlspecialchars($email) ?></p>
                    <p><strong>الدور:</strong> <?= htmlspecialchars($userRole) ?></p>
                </div>
                <p style="margin-top: 20px;">
                    <a href="admin/login.php" style="color: #667eea; text-decoration: none; font-weight: bold;">
                        ← الذهاب لصفحة تسجيل الدخول
                    </a>
                </p>
            </div>

            <div class="delete-notice">
                ⚠️ لا تنسَ حذف هذا الملف الآن: change_admin_password.php
            </div>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('new_password');
            const btn = document.querySelector('.toggle-btn');
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

