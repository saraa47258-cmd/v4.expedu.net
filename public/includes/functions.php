<?php
// includes/functions.php
require __DIR__ . '/../../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$config = require __DIR__ . '/config.php';

function getPDO(){
    global $config;
    $db = $config->db;
    $dsn = "mysql:host={$db->host};dbname={$db->name};charset={$db->charset}";
    return new PDO($dsn, $db->user, $db->pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

function getS3Client(){
    global $config;
    return new S3Client([
        'version' => 'latest',
        'region'  => $config->wasabi->region,
        'endpoint' => $config->wasabi->endpoint,
        'use_path_style_endpoint' => true,
        'credentials' => [
            'key' => $config->wasabi->key,
            'secret' => $config->wasabi->secret,
        ],
    ]);
}

// رفع ملف من الخادم إلى Wasabi
function uploadFileToWasabi($localPath, $destKey, $contentType = null){
    global $config;
    $s3 = getS3Client();
    try {
        $params = [
            'Bucket' => $config->wasabi->bucket,
            'Key' => $destKey,
            'SourceFile' => $localPath,
            'ACL' => 'private'
        ];
        if($contentType) $params['ContentType'] = $contentType;
        
        $result = $s3->putObject($params);
        
        // إنشاء URL للملف المرفوع
        $fileUrl = $config->wasabi->endpoint . '/' . $config->wasabi->bucket . '/' . $destKey;
        
        // تسجيل نجاح الرفع
        error_log("File uploaded successfully: " . $destKey);
        
        return $fileUrl;
    } catch (AwsException $e) {
        error_log("Wasabi upload error: " . $e->getMessage());
        error_log("Error details: " . $e->getAwsErrorMessage());
        return false;
    } catch (Exception $e) {
        error_log("General upload error: " . $e->getMessage());
        return false;
    }
}

// توليد رابط توقيعي مؤقت للوصول (GET) للملف
function getPresignedUrl($key, $expires = '+60 minutes'){
    $s3 = getS3Client();
    global $config;
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $config->wasabi->bucket,
        'Key' => $key
    ]);
    $request = $s3->createPresignedRequest($cmd, $expires);
    return (string) $request->getUri();
}

// توليد رابط توقيعي يسمح بالمشاهدة داخل المتصفح ويقلل احتمالية التحميل
function getPresignedInlineUrl(string $key, string $expires = '+15 minutes', string $contentType = 'video/mp4', ?string $downloadName = null){
    $s3 = getS3Client();
    global $config;
    if ($downloadName === null) {
        $downloadName = basename($key);
    }
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $config->wasabi->bucket,
        'Key' => $key,
        // إجبار العرض داخل المتصفح
        'ResponseContentDisposition' => 'inline; filename="' . $downloadName . '"',
        'ResponseContentType' => $contentType,
    ]);
    $request = $s3->createPresignedRequest($cmd, $expires);
    return (string)$request->getUri();
}

// توليد slug بسيط
function slugify($text){
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8','us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) return 'n-a';
    return $text;
}
