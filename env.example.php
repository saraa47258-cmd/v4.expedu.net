<?php
/**
 * مثال على ملف متغيرات البيئة
 * انسخ هذا الملف إلى env.php وعدّل القيم
 */
return [
    // Database Configuration
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'your_database_name',
    'DB_USER' => 'your_username',
    'DB_PASS' => 'your_password',
    'DB_CHARSET' => 'utf8mb4',

    // Wasabi S3 Configuration
    'WASABI_KEY' => 'your_wasabi_access_key',
    'WASABI_SECRET' => 'your_wasabi_secret_key',
    'WASABI_REGION' => 'ap-southeast-1',
    'WASABI_BUCKET' => 'your_bucket_name',
    'WASABI_ENDPOINT' => 'https://s3.ap-southeast-1.wasabisys.com',

    // Application Configuration
    'APP_BASE_URL' => 'http://localhost/expedu/public',
    'APP_ENV' => 'production',
    'APP_DEBUG' => false
];


