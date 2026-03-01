<?php
/**
 * ملف الإعدادات الرئيسي
 * يقرأ من متغيرات البيئة أو من ملف env.php
 */

// تحميل متغيرات البيئة من ملف env.php إذا وُجد
$envFile = __DIR__ . '/../../env.php';
if (file_exists($envFile)) {
    $envVars = require $envFile;
    foreach ($envVars as $key => $value) {
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
    }
}

// دالة مساعدة لقراءة متغيرات البيئة
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

return (object) [
    'db' => (object) [
        'host' => env('DB_HOST', '127.0.0.1'),
        'name' => env('DB_NAME', 'expedun1_talent'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4')
    ],
    'wasabi' => (object) [
        'key' => env('WASABI_KEY', ''),
        'secret' => env('WASABI_SECRET', ''),
        'region' => env('WASABI_REGION', 'ap-southeast-1'),
        'bucket' => env('WASABI_BUCKET', 'expedu-courses'),
        'endpoint' => env('WASABI_ENDPOINT', 'https://s3.ap-southeast-1.wasabisys.com')
    ],
    'app' => (object) [
        'base_url' => env('APP_BASE_URL', 'http://localhost/expedu/public'),
        'env' => env('APP_ENV', 'development'),
        'debug' => env('APP_DEBUG', false)
    ]
];
