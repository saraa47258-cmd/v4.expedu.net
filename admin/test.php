<?php
echo "<h1>✅ مجلد admin يعمل!</h1>";
echo "<hr>";
echo "<h2>معلومات المسارات:</h2>";
echo "<p><strong>المسار الحالي:</strong> " . __DIR__ . "</p>";
echo "<p><strong>الملف الحالي:</strong> " . __FILE__ . "</p>";
echo "<hr>";

echo "<h2>🔗 اختبر هذه الروابط:</h2>";
echo "<ul>";
echo "<li><a href='index.php'>admin/index.php (لوحة الأدمن)</a></li>";
echo "<li><a href='dashboard.php'>admin/dashboard.php (لوحة التحكم الرئيسية)</a></li>";
echo "<li><a href='register.php'>admin/register.php (إضافة مستخدم)</a></li>";
echo "<li><a href='users.php'>admin/users.php (إدارة المستخدمين)</a></li>";
echo "<li><a href='courses.php'>admin/courses.php (إدارة الكورسات)</a></li>";
echo "<li><a href='upload.php'>admin/upload.php (رفع كورس)</a></li>";
echo "<li><a href='ads.php'>admin/ads.php (إدارة الإعلانات)</a></li>";
echo "<li><a href='../public/login.php'>../public/login.php (تسجيل دخول)</a></li>";
echo "<li><a href='../public/courses.php'>../public/courses.php (عرض الكورسات)</a></li>";
echo "<li><a href='../public/index.php'>../public/index.php (الصفحة الرئيسية)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h2>📂 فحص الملفات:</h2>";

$files = [
    '../public/includes/functions.php',
    '../public/includes/auth.php',
    '../public/login.php',
    '../public/logout.php',
    '../public/register.php',
    '../public/courses.php',
    '../public/index.php',
    'dashboard.php',
    'register.php',
    'users.php'
];

foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    $status = $exists ? '✅' : '❌';
    echo "<p>$status $file</p>";
}

echo "<hr>";
echo "<h2>🔐 حالة الجلسة:</h2>";
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<p>✅ <strong>مسجل الدخول:</strong> " . htmlspecialchars($_SESSION['user_name'] ?? 'مستخدم') . "</p>";
    echo "<p>📧 <strong>البريد:</strong> " . htmlspecialchars($_SESSION['user_email'] ?? '') . "</p>";
    echo "<p>🎭 <strong>الصلاحية:</strong> " . htmlspecialchars($_SESSION['user_role'] ?? '') . "</p>";
} else {
    echo "<p>❌ <strong>غير مسجل الدخول</strong></p>";
    echo "<p>👉 <a href='../public/login.php'>سجل الدخول هنا</a></p>";
}
?>
