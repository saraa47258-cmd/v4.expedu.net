<?php
// نسخة احتياطية من login.php القديم
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) $errors[] = 'أدخل البريد وكلمة المرور.';
    if (!$errors && !login($email, $password)) $errors[] = 'بيانات الدخول غير صحيحة.';
    if (!$errors) {
        $next = $_GET['next'] ?? '../admin/dashboard.php';
        header('Location: ' . $next);
        exit;
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تسجيل الدخول - ExpEdu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:520px">
  <h3 class="mb-3">تسجيل الدخول</h3>
  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0"><?php foreach($errors as $er) echo "<li>".htmlspecialchars($er)."</li>"; ?></ul>
    </div>
  <?php endif; ?>
  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">البريد الإلكتروني</label>
      <input class="form-control" name="email" type="email" required>
    </div>
    <div class="mb-3">
      <label class="form-label">كلمة المرور</label>
      <input class="form-control" name="password" type="password" required>
    </div>
    <button class="btn btn-primary w-100">دخول</button>
    <div class="text-center mt-3">
      ليس لديك حساب؟ <a href="register.php">إنشاء حساب</a>
    </div>
  </form>
</div>
</body>
</html>

