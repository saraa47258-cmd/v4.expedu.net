<?php
/**
 * Secure stream proxy - requires login and verifies access
 * يتحقق من اشتراك المستخدم في الكورس قبل السماح بالمشاهدة
 */
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';

// التحقق من تسجيل الدخول
require_login();

$key = $_GET['key'] ?? '';
if (!$key) {
    http_response_code(400);
    echo 'Missing key';
    exit;
}

// التحقق من صحة المفتاح (منع path traversal)
if (strpos($key, '..') !== false || strpos($key, '//') !== false) {
    http_response_code(400);
    echo 'Invalid key';
    exit;
}

$user = current_user();
$pdo = getPDO();

// التحقق من الصلاحيات
$hasAccess = false;

// الأدمن والمشرفين يمكنهم الوصول لكل الدروس
if (in_array($user['role'], ['admin', 'instructor'])) {
    $hasAccess = true;
} else {
    // للطلاب: التحقق من الاشتراك في الكورس
    // البحث عن الدرس والكورس
    $lessonStmt = $pdo->prepare("
        SELECT l.id, l.course_id, c.instructor_id, c.price
        FROM lessons l
        JOIN courses c ON c.id = l.course_id
        WHERE l.wasabi_key = ?
    ");
    $lessonStmt->execute([$key]);
    $lesson = $lessonStmt->fetch();
    
    if ($lesson) {
        // إذا كان الكورس مجاني
        if ((float)$lesson['price'] == 0) {
            $hasAccess = true;
        } else {
            // التحقق من وجود اشتراك للمستخدم في هذا الكورس
            // ملاحظة: قد تحتاج لإنشاء جدول enrollments إذا لم يكن موجوداً
            $enrollStmt = $pdo->prepare("
                SELECT id FROM enrollments 
                WHERE user_id = ? AND course_id = ? AND status = 'active'
            ");
            $enrollStmt->execute([$user['id'], $lesson['course_id']]);
            if ($enrollStmt->fetch()) {
                $hasAccess = true;
            }
        }
    }
}

if (!$hasAccess) {
    http_response_code(403);
    echo '<!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>غير مسموح</title>
        <style>
            body { font-family: Cairo, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
            .box { background: white; padding: 40px; border-radius: 15px; max-width: 500px; margin: 0 auto; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
            h1 { color: #E74C60; }
            a { display: inline-block; margin-top: 20px; padding: 10px 30px; background: #066755; color: white; text-decoration: none; border-radius: 10px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>⛔ غير مسموح</h1>
            <p>يجب عليك الاشتراك في هذا الكورس لمشاهدة الدروس.</p>
            <a href="courses.php">تصفح الكورسات</a>
        </div>
    </body>
    </html>';
    exit;
}

// Guess content type from extension for better inline playback
$ext = strtolower(pathinfo($key, PATHINFO_EXTENSION));
$mime = 'application/octet-stream';
if (in_array($ext, ['mp4','webm','mov'])) $mime = 'video/' . ($ext === 'mp4' ? 'mp4' : ($ext === 'mov' ? 'quicktime' : 'webm'));
if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) $mime = 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext);

// Short expiry and inline to reduce shareability and downloading
$url = getPresignedInlineUrl($key, '+10 minutes', $mime);

// Enforce no-store on this redirect response
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Referrer-Policy: no-referrer');

// Redirect to signed URL
header('Location: ' . $url, true, 302);
exit;
