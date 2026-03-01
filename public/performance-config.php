<?php
/**
 * إعدادات تحسين الأداء
 */

// تفعيل ضغط GZIP
if (!ob_start("ob_gzhandler")) {
    ob_start();
}

// تفعيل التخزين المؤقت
header("Cache-Control: public, max-age=3600");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT");

// ضغط HTML
function sanitize_output($buffer) {
    $search = [
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    ];
    
    $replace = [
        '>',
        '<',
        '\\1',
        ''
    ];
    
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

if (!isset($_GET['debug'])) {
    ob_start("sanitize_output");
}
?>


