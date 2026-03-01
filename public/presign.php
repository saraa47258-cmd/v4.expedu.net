<?php
// public/presign.php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$name = basename($_GET['name'] ?? ('upload-' . time()));
$mime = $_GET['type'] ?? 'application/octet-stream';
$prefix = trim($_GET['prefix'] ?? 'uploads', '/'); // مثال: courses/slug
$key = $prefix . '/' . $name;

try {
    $s3 = getS3Client();
    global $config;

    $cmd = $s3->getCommand('PutObject', [
        'Bucket' => $config->wasabi->bucket,
        'Key'    => $key,
        'ContentType' => $mime,
        'ACL' => 'private'
    ]);
    $req = $s3->createPresignedRequest($cmd, '+1 hour');
    echo json_encode([
        'key' => $key,
        'url' => (string)$req->getUri()
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
