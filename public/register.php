<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') $errors[] = 'الرجاء تعبئة جميع الحقول.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد الإلكتروني غير صالح.';
    if ($password !== $confirm) $errors[] = 'كلمتا المرور غير متطابقتين.';

    if (!$errors) {
        try {
            $pdo = getPDO();
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT), 'instructor']);
            // تسجيل الدخول تلقائيًا
            login($email, $password);
            header('Location: ../admin/dashboard.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'هذا البريد مستخدم من قبل.';
            else $errors[] = 'حدث خطأ غير متوقع.';
        }
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تسجيل حساب مدرب - ExpEdu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:520px">
  <h3 class="mb-3">إنشاء حساب</h3>
  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0"><?php foreach($errors as $er) echo "<li>".htmlspecialchars($er)."</li>"; ?></ul>
    </div>
  <?php endif; ?>
  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">الاسم</label>
      <input class="form-control" name="name" required value="<?=htmlspecialchars($_POST['name'] ?? '')?>">
    </div>
    <div class="mb-3">
      <label class="form-label">البريد الإلكتروني</label>
      <input class="form-control" name="email" type="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
    </div>
    <div class="mb-3">
      <label class="form-label">كلمة المرور</label>
      <input class="form-control" name="password" type="password" required>
    </div>
    <div class="mb-3">
      <label class="form-label">تأكيد كلمة المرور</label>
      <input class="form-control" name="confirm" type="password" required>
    </div>
    <button class="btn btn-primary w-100">تسجيل</button>
    <div class="text-center mt-3">
      لديك حساب؟ <a href="login.php">تسجيل الدخول</a>
    </div>
  </form>
</div>
</body>
</html>
