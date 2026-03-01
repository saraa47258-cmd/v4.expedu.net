<?php
/**
 * صفحة تسجيل الدخول الموحدة
 * توجه المستخدمين إلى صفحة تسجيل الدخول المناسبة
 * 
 * - إذا جاء الطلب من admin/ → يوجه إلى admin/login.php
 * - غير ذلك → يوجه إلى user_login.php
 */
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

$currentUser = current_user();

// إذا كان مسجل دخول، وجّه للوحة التحكم المناسبة
if ($currentUser) {
    if (in_array($currentUser['role'], ['admin', 'instructor'])) {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: user_dashboard.php');
    }
    exit;
}

// الحفاظ على next parameter
$next = $_GET['next'] ?? '';
$queryString = $next ? '?next=' . urlencode($next) : '';

// التحقق إذا كان الطلب من admin/
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$isAdminContext = strpos($referer, '/admin/') !== false || strpos($next, 'admin') !== false;

if ($isAdminContext) {
    header('Location: ../admin/login.php' . $queryString);
} else {
    header('Location: user_login.php' . $queryString);
}
exit;
