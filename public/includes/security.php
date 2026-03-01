<?php
/**
 * ملف الأمان والحماية
 * يحتوي على دوال للتحقق من الصلاحيات وحماية الموقع
 */

/**
 * Rate Limiting - الحد من المحاولات المتكررة
 * يمنع محاولات Brute Force على تسجيل الدخول
 */
function checkRateLimit($identifier, $maxAttempts = 5, $windowMinutes = 15) {
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($identifier) . '.json';
    
    $attempts = [];
    if (file_exists($cacheFile)) {
        $content = file_get_contents($cacheFile);
        $attempts = json_decode($content, true) ?: [];
    }
    
    // تنظيف المحاولات القديمة
    $windowStart = time() - ($windowMinutes * 60);
    $attempts = array_filter($attempts, function($timestamp) use ($windowStart) {
        return $timestamp > $windowStart;
    });
    
    // التحقق من عدد المحاولات
    if (count($attempts) >= $maxAttempts) {
        return false; // تم تجاوز الحد
    }
    
    return true;
}

/**
 * تسجيل محاولة (ناجحة أو فاشلة)
 */
function recordAttempt($identifier) {
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($identifier) . '.json';
    
    $attempts = [];
    if (file_exists($cacheFile)) {
        $content = file_get_contents($cacheFile);
        $attempts = json_decode($content, true) ?: [];
    }
    
    $attempts[] = time();
    file_put_contents($cacheFile, json_encode($attempts));
}

/**
 * مسح محاولات (بعد تسجيل دخول ناجح)
 */
function clearAttempts($identifier) {
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($identifier) . '.json';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
}

/**
 * التحقق من أن الطلب من نفس الدومين (حماية من CSRF)
 */
function verifyReferer() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $host = $_SERVER['HTTP_HOST'];
        return $referer === $host;
    }
    return false;
}

/**
 * تنظيف المدخلات من HTML و XSS
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * التحقق من صحة عنوان البريد الإلكتروني
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * التحقق من قوة كلمة المرور
 */
function isStrongPassword($password) {
    // 8 أحرف على الأقل
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل'];
    }
    
    // حرف كبير
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل'];
    }
    
    // حرف صغير
    if (!preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'message' => 'كلمة المرور يجب أن تحتوي على حرف صغير واحد على الأقل'];
    }
    
    // رقم
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل'];
    }
    
    return ['valid' => true, 'message' => 'كلمة مرور قوية'];
}

/**
 * التحقق من صلاحية الوصول للملف
 */
function isValidFilePath($path) {
    // منع Path Traversal
    if (strpos($path, '..') !== false || strpos($path, '//') !== false) {
        return false;
    }
    
    // منع الأحرف الخاصة
    if (preg_match('/[<>:"\/\\|?*\x00-\x1F]/', $path)) {
        return false;
    }
    
    return true;
}

/**
 * التحقق من نوع MIME للملف
 */
function validateMimeType($filePath, $allowedTypes) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($filePath);
    
    return in_array($mimeType, $allowedTypes);
}

/**
 * إنشاء Token عشوائي آمن
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * تشفير البيانات
 */
function encryptData($data, $key) {
    $method = 'aes-256-cbc';
    $iv = random_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

/**
 * فك تشفير البيانات
 */
function decryptData($encryptedData, $key) {
    $method = 'aes-256-cbc';
    $data = base64_decode($encryptedData);
    $ivLength = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $method, $key, 0, $iv);
}

/**
 * إضافة Headers أمان للاستجابة
 */
function addSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:;");
}

/**
 * التحقق من صلاحية الـ IP
 */
function isValidIP($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

/**
 * الحصول على IP الزائر الحقيقي
 */
function getClientIP() {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            if (isValidIP($ip)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}


