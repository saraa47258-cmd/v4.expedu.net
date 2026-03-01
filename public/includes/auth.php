<?php
// includes/auth.php
require_once __DIR__ . '/functions.php';
session_start();

function current_user() {
    if (!isset($_SESSION['user_id'])) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'student',
    ];
}

function require_login() {
    if (!current_user()) {
        // توجيه إلى صفحة تسجيل دخول المستخدمين
        header('Location: user_login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Admin-only helpers
function is_admin() {
    $u = current_user();
    return $u && $u['role'] === 'admin';
}

function is_instructor() {
    $u = current_user();
    return $u && ($u['role'] === 'instructor');
}

// Use ONLY from files under admin/ directory
function require_admin() {
    if (!current_user()) {
        // من admin/ إلى admin/login.php
        header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
    if (!is_admin()) {
        http_response_code(403);
        echo '<h1>ممنوع - Forbidden</h1><p>هذه الصفحة مخصصة للأدمن فقط.</p><a href="login.php">تسجيل الدخول</a>';
        exit;
    }
}

function require_role($roles = ['admin','instructor']) {
    $u = current_user();
    if (!$u) {
        header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
    if (!in_array($u['role'], (array)$roles, true)) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

function login($email, $password) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($password, $u['password_hash'])) {
        // تجديد session ID لمنع session fixation attacks
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = (int)$u['id'];
        $_SESSION['user_name'] = $u['name'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_role'] = $u['role'];
        return true;
    }
    return false;
}

function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

// ===================================
// CSRF Protection Functions
// ===================================

/**
 * توليد CSRF Token
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * التحقق من CSRF Token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * إنشاء حقل CSRF مخفي للنماذج
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * التحقق من CSRF في POST requests
 */
function check_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf_token($token)) {
            http_response_code(403);
            die('خطأ أمني: CSRF Token غير صحيح');
        }
    }
}
