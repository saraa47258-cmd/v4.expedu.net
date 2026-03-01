<?php
/**
 * صفحة انضم إلينا - بوابة خبرة
 */
require_once __DIR__ . '/includes/functions.php';
session_start();

$success = false;
$error = null;
$requestType = '';

// دالة للتحقق من نوع MIME الفعلي للملف
function validateFileType($filePath, $allowedTypes) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($filePath);
    return in_array($mimeType, $allowedTypes);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // التحقق من CSRF Token
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            throw new Exception('خطأ أمني: يرجى تحديث الصفحة والمحاولة مرة أخرى');
        }
        
        $pdo = getPDO();
        
        $requestType = $_POST['request_type'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        // التحقق من الحقول الأساسية
        if (empty($fullName) || empty($email) || empty($phone)) {
            throw new Exception('جميع الحقول الأساسية مطلوبة');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('البريد الإلكتروني غير صحيح');
        }
        
        // تجهيز البيانات حسب النوع
        $data = [
            'request_type' => $requestType,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'specialization' => null,
            'experience_description' => null,
            'cv_file_path' => null,
            'supervision_field' => null,
            'previous_supervision_experience' => null,
            'institution_name' => null,
            'responsible_person' => null,
            'institution_type' => null,
            'services_required' => null,
            'learner_category' => null,
            'learning_field' => null,
            'skills_required' => null,
            'specific_service' => null
        ];
        
        // ملء البيانات حسب نوع الطلب
        switch ($requestType) {
            case 'expert':
                $data['specialization'] = trim($_POST['specialization'] ?? '');
                $data['experience_description'] = trim($_POST['experience_description'] ?? '');
                // معالجة رفع السيرة الذاتية (اختياري)
                if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
                    // التحقق من حجم الملف (الحد الأقصى 5 ميجا)
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    if ($_FILES['cv_file']['size'] > $maxSize) {
                        throw new Exception('حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت.');
                    }
                    
                    // قائمة أنواع MIME المسموحة
                    $allowedMimeTypes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ];
                    
                    // التحقق من نوع MIME الفعلي
                    if (!validateFileType($_FILES['cv_file']['tmp_name'], $allowedMimeTypes)) {
                        throw new Exception('نوع الملف غير مسموح. يرجى رفع ملف PDF أو Word فقط.');
                    }
                    
                    // التحقق من امتداد الملف
                    $allowedExtensions = ['pdf', 'doc', 'docx'];
                    $ext = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExtensions)) {
                        throw new Exception('امتداد الملف غير مسموح. يرجى رفع ملف PDF أو Word فقط.');
                    }
                    
                    $uploadDir = __DIR__ . '/uploads/cvs/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // إنشاء اسم آمن للملف
                    $fileName = uniqid('cv_', true) . '.' . $ext;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $targetPath)) {
                        $data['cv_file_path'] = 'uploads/cvs/' . $fileName;
                    }
                }
                break;
                
            case 'supervisor':
                $data['supervision_field'] = trim($_POST['supervision_field'] ?? '');
                $data['previous_supervision_experience'] = trim($_POST['previous_supervision_experience'] ?? '');
                break;
                
            case 'institution':
                $data['institution_name'] = trim($_POST['institution_name'] ?? '');
                $data['responsible_person'] = $fullName;
                $data['institution_type'] = $_POST['institution_type'] ?? null;
                $data['services_required'] = trim($_POST['services_required'] ?? '');
                break;
                
            case 'learner':
                $data['learner_category'] = $_POST['learner_category'] ?? null;
                $data['learning_field'] = trim($_POST['learning_field'] ?? '');
                $data['skills_required'] = trim($_POST['skills_required'] ?? '');
                $data['specific_service'] = trim($_POST['specific_service'] ?? '');
                break;
                
            default:
                throw new Exception('نوع الطلب غير صحيح');
        }
        
        // إدخال البيانات في قاعدة البيانات
        $sql = "INSERT INTO join_requests (
            request_type, full_name, email, phone,
            specialization, experience_description, cv_file_path,
            supervision_field, previous_supervision_experience,
            institution_name, responsible_person, institution_type, services_required,
            learner_category, learning_field, skills_required, specific_service
        ) VALUES (
            :request_type, :full_name, :email, :phone,
            :specialization, :experience_description, :cv_file_path,
            :supervision_field, :previous_supervision_experience,
            :institution_name, :responsible_person, :institution_type, :services_required,
            :learner_category, :learning_field, :skills_required, :specific_service
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        
        $success = true;
        
        // إعادة تعيين النموذج
        $_POST = [];
        
    } catch (PDOException $e) {
        // تسجيل الخطأ في log بدون عرضه للمستخدم
        error_log('Join Request DB Error: ' . $e->getMessage());
        $error = 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة لاحقاً.';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// إنشاء CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انضم إلينا - بوابة خبرة </title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Navbar & Footer CSS -->
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #066755;
            --primary-light: #0a8a6b;
            --primary-dark: #044a3d;
            --accent: #ffd700;
            --accent-hover: #ffaa00;
            --dark: #0a1628;
            --gray-100: #f8f9fa;
            --white: #ffffff;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }
        
        body {
            background: var(--gray-100);
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-join {
            position: relative;
            padding: 180px 0 120px;
            background: linear-gradient(160deg, var(--primary) 0%, var(--primary-dark) 50%, #022b23 100%);
            overflow: hidden;
        }
        
        .hero-join::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,215,0,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }
        
        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,215,0,0.15);
            border: 1px solid rgba(255,215,0,0.3);
            padding: 10px 24px;
            border-radius: 50px;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 25px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .hero-title span {
            color: var(--accent);
        }
        
        .hero-desc {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.85);
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.8;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
        }
        
        .hero-stat {
            text-align: center;
        }
        
        .hero-stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--accent);
        }
        
        .hero-stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        
        /* Cards Section */
        .cards-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #f0f7f5 0%, var(--white) 100%);
            margin-top: -60px;
            position: relative;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6,103,85,0.1);
            padding: 10px 24px;
            border-radius: 50px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--dark);
        }
        
        .section-title span {
            color: var(--primary);
        }
        
        /* Join Cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        
        .join-card {
            background: var(--white);
            border-radius: 28px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            transition: all 0.4s ease;
            border: 1px solid rgba(6,103,85,0.08);
            position: relative;
            overflow: hidden;
            text-align: center;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .join-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .join-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 60px rgba(6,103,85,0.15);
            border-color: var(--primary);
        }
        
        .join-card:hover::before {
            transform: scaleX(1);
        }
        
        .join-card.expert .card-icon-wrapper { background: linear-gradient(135deg, #066755, #0a8a6b); }
        .join-card.supervisor .card-icon-wrapper { background: linear-gradient(135deg, #7b2cbf, #9d4edd); }
        .join-card.institution .card-icon-wrapper { background: linear-gradient(135deg, #0066cc, #00aaff); }
        .join-card.learner .card-icon-wrapper { background: linear-gradient(135deg, #e65100, #ff8f00); }
        
        .card-icon-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.5rem;
            color: var(--white);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            transition: all 0.4s;
        }
        
        .join-card:hover .card-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .card-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .card-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            flex-grow: 1;
        }
        
        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .feature-tag {
            background: rgba(6,103,85,0.08);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .btn-join {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: var(--white) !important;
            padding: 16px 30px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(6,103,85,0.25);
            width: 100%;
        }
        
        .btn-join:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(6,103,85,0.35);
            color: var(--white);
        }
        
        /* Why Join Section */
        .why-section {
            padding: 100px 0;
            background: var(--white);
        }
        
        .why-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        
        .why-card {
            text-align: center;
            padding: 30px 20px;
        }
        
        .why-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--white);
            box-shadow: 0 10px 25px rgba(6,103,85,0.25);
        }
        
        .why-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .why-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        /* Stats Banner */
        .stats-banner {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .stats-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 50%, rgba(255,215,0,0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 50%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            position: relative;
            z-index: 2;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
            color: var(--primary-dark);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
        }
        
        .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            margin-top: 8px;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(180deg, var(--white) 0%, #f0f7f5 100%);
        }
        
        .cta-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 40px;
            padding: 80px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,215,0,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.5rem;
            color: var(--accent);
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--white);
            margin-bottom: 15px;
        }
        
        .cta-desc {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 35px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: var(--primary-dark) !important;
            padding: 18px 40px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(255,215,0,0.3);
        }
        
        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255,215,0,0.4);
        }
        
        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            color: var(--white) !important;
            padding: 16px 38px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-3px);
        }
        
        /* Footer */
        .footer-mini {
            background: var(--dark);
            padding: 30px 0;
            text-align: center;
        }
        
        .footer-mini p {
            color: rgba(255,255,255,0.6);
            margin: 0;
        }
        
        .footer-mini a {
            color: var(--accent);
            text-decoration: none;
        }
        
        /* Alerts */
        .alert-custom {
            border-radius: 16px;
            padding: 20px 25px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            margin-bottom: 30px;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        /* Modal Styling */
        .modal-content {
            border-radius: 28px;
            border: none;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px 35px;
            border: none;
        }
        
        .modal-title {
            font-size: 1.6rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .modal-title i {
            font-size: 1.4rem;
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
        
        .btn-close:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 40px 35px;
            background: var(--gray-100);
        }
        
        .form-label {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .required {
            color: #dc3545;
            margin-right: 3px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 1rem;
            background: var(--white);
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(6, 103, 85, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 18px;
            background: var(--white);
            border: 2px dashed var(--primary);
            border-radius: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--primary);
            font-weight: 600;
        }
        
        .file-upload-label:hover {
            background: rgba(6,103,85,0.05);
            border-color: var(--primary-light);
        }
        
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 16px;
            font-size: 1.2rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s;
            margin-top: 25px;
            box-shadow: 0 10px 30px rgba(6, 103, 85, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(6, 103, 85, 0.4);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .why-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                gap: 30px;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .why-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-card {
                padding: 50px 25px;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-stats {
                flex-wrap: wrap;
                gap: 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .cta-card {
                padding: 40px 20px;
            }
        }
        
        @media (max-width: 374.98px) {
            .hero-title {
                font-size: 1.6rem;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 12px;
                align-items: center;
            }
            
            .hero-stat-number {
                font-size: 1.8rem;
            }
            
            .stat-item {
                padding: 20px;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .type-card {
                padding: 24px 16px;
            }
            
            .why-card {
                padding: 24px 16px;
            }
            
            .cta-card {
                padding: 30px 16px;
                border-radius: 24px;
            }
            
            .cta-title {
                font-size: 1.3rem;
            }
            
            .cta-desc {
                font-size: 0.95rem;
            }

            .btn-cta-primary,
            .btn-cta-secondary {
                padding: 14px 24px;
                font-size: 0.95rem;
                width: 100%;
                justify-content: center;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
            
            .hero-desc {
                font-size: 1rem;
                margin-bottom: 24px;
            }
            
            .modal-header {
                padding: 20px 16px;
            }
            .modal-body {
                padding: 24px 16px;
            }
            .modal-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body class="has-premium-navbar">
    <!-- Premium Navbar -->
    <?php $currentPage = 'join'; include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-join">
        <div class="hero-pattern"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-handshake"></i>
                    كن جزءاً من فريقنا
                </div>
                <h1 class="hero-title">
                    انضم إلينا في <span>بوابة خبرة</span>
                </h1>
                <p class="hero-desc">
                    سواء كنت خبيراً، مشرفاً، مؤسسة، أو متعلماً، نقدِّم لك الفرصة لصناعة أثر حقيقي في مجتمع التعلم والتطوير
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">+320</div>
                        <div class="hero-stat-label">خبير ومشرف</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">+2200</div>
                        <div class="hero-stat-label">متعلم مستفيد</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">+50</div>
                        <div class="hero-stat-label">مؤسسة شريكة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Alerts -->
    <?php if ($success): ?>
        <div class="container mt-5">
            <div class="alert alert-success alert-custom" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>تم إرسال طلبك بنجاح!</strong> سنتواصل معك قريباً.
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="container mt-5">
            <div class="alert alert-danger alert-custom" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>خطأ:</strong> <?= htmlspecialchars($error) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Cards Section -->
    <section class="cards-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-users"></i>
                    اختر طريقتك
                </div>
                <h2 class="section-title">كيف تريد أن <span>تنضم</span> إلينا؟</h2>
            </div>
            
            <div class="cards-grid">
                <!-- بطاقة الخبير -->
                <div class="join-card expert">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="card-title">انضم كخبير</h3>
                    <p class="card-description">
                        شارك معرفتك وخبرتك مع المتعلِّمين وكن جزءاً من شبكة خبراء بوابة خبرة
                    </p>
                    <div class="card-features">
                        <span class="feature-tag">تدريب</span>
                        <span class="feature-tag">استشارات</span>
                    </div>
                    <button class="btn btn-join" data-bs-toggle="modal" data-bs-target="#expertModal">
                        <i class="fas fa-user-plus"></i>
                        سجّل كخبير
                    </button>
                </div>
                
                <!-- بطاقة المشرف -->
                <div class="join-card supervisor">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="card-title">شارك كمشرف</h3>
                    <p class="card-description">
                        ساعدنا في الإشراف على الدورات والورش وتقديم التوجيه الأكاديمي والتدريبي
                    </p>
                    <div class="card-features">
                        <span class="feature-tag">إشراف</span>
                        <span class="feature-tag">توجيه</span>
                    </div>
                    <button class="btn btn-join" data-bs-toggle="modal" data-bs-target="#supervisorModal">
                        <i class="fas fa-clipboard-check"></i>
                        سجّل كمشرف
                    </button>
                </div>
                
                <!-- بطاقة المؤسسة -->
                <div class="join-card institution">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="card-title">تعاون كمؤسسة</h3>
                    <p class="card-description">
                        عزّز قدرات فريقك وادعم مجتمع المتعلِّمين من خلال شراكة استراتيجية معنا
                    </p>
                    <div class="card-features">
                        <span class="feature-tag">شراكة</span>
                        <span class="feature-tag">تدريب مؤسسي</span>
                    </div>
                    <button class="btn btn-join" data-bs-toggle="modal" data-bs-target="#institutionModal">
                        <i class="fas fa-handshake"></i>
                        تعاون معنا
                    </button>
                </div>
                
                <!-- بطاقة المتعلم -->
                <div class="join-card learner">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h3 class="card-title">ابدأ رحلتك كمتعلِّم</h3>
                    <p class="card-description">
                        استكشف الدورات والخدمات التي تساعدك على التطور الشخصي والمهني
                    </p>
                    <div class="card-features">
                        <span class="feature-tag">دورات</span>
                        <span class="feature-tag">تطوير</span>
                    </div>
                    <button class="btn btn-join" data-bs-toggle="modal" data-bs-target="#learnerModal">
                        <i class="fas fa-rocket"></i>
                        ابدأ الآن
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Join Section -->
    <section class="why-section">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-star"></i>
                    مميزاتنا
                </div>
                <h2 class="section-title">لماذا <span>تنضم</span> إلينا؟</h2>
            </div>
            
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h4 class="why-title">شبكة واسعة</h4>
                    <p class="why-desc">انضم لشبكة تضم أكثر من 320 خبير ومشرف في مختلف التخصصات</p>
                </div>
                
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="why-title">نمو مستمر</h4>
                    <p class="why-desc">فرص للتطور المهني والشخصي من خلال برامج التدريب والتأهيل</p>
                </div>
                
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h4 class="why-title">أثر حقيقي</h4>
                    <p class="why-desc">ساهم في صناعة التغيير الإيجابي في المجتمع من خلال التعليم</p>
                </div>
                
                <div class="why-card">
                    <div class="why-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4 class="why-title">تقدير واعتراف</h4>
                    <p class="why-desc">احصل على شهادات معتمدة وتقدير لإسهاماتك ومشاركاتك</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-number">+320</div>
                    <div class="stat-label">خبير ومشرف</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">+2200</div>
                    <div class="stat-label">طالب مستفيد</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">+50</div>
                    <div class="stat-label">مؤسسة شريكة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-number">+50</div>
                    <div class="stat-label">دورة تدريبية</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h2 class="cta-title">لديك استفسار؟</h2>
                    <p class="cta-desc">
                        تواصل معنا وسنساعدك في الإجابة على جميع تساؤلاتك
                    </p>
                    <div class="cta-buttons">
                        <a href="index.php#contact" class="btn-cta-primary">
                            <i class="fas fa-envelope"></i>
                            تواصل معنا
                        </a>
                        <a href="https://wa.me/96899999999" class="btn-cta-secondary">
                            <i class="fab fa-whatsapp"></i>
                            واتساب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Premium Footer -->
    <footer class="premium-footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#066755" d="M0,60 C360,120 1080,0 1440,60 L1440,120 L0,120 Z"></path>
            </svg>
        </div>
        <div class="footer-main">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="footer-brand">
                            <div class="brand-logo">
                                <img src="assets/icons/logo.png" alt="بوابة خبرة">
                                <div class="brand-text"><h4>بوابة خبرة</h4></div>
                            </div>
                            <p class="brand-desc">منصتك للتعلّم والتطوير وصناعة المستقبل. نُمكّن الأفراد والمؤسسات منذ 2020م عبر دورات تخصصية وخدمات متكاملة.</p>
                            <div class="contact-cards">
                                <a href="tel:+96892332749" class="contact-card">
                                    <div class="contact-icon phone"><i class="fas fa-phone-alt"></i></div>
                                    <div class="contact-details"><span class="label">اتصل بنا</span><span class="value" dir="ltr">+968 92332749</span></div>
                                </a>
                                <a href="mailto:info.expertplatform@gmail.com" class="contact-card">
                                    <div class="contact-icon email"><i class="fas fa-envelope"></i></div>
                                    <div class="contact-details"><span class="label">البريد الإلكتروني</span><span class="value">info.expertplatform@gmail.com</span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-link"></i> روابط سريعة</h5>
                                    <ul>
                                        <li><a href="index.php"><i class="fas fa-chevron-left"></i> الرئيسية</a></li>
                                        <li><a href="about.php"><i class="fas fa-chevron-left"></i> من نحن</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> خدماتنا</a></li>
                                        <li><a href="courses.php"><i class="fas fa-chevron-left"></i> الكورسات</a></li>
                                        <li><a href="team.php"><i class="fas fa-chevron-left"></i> فريقنا</a></li>
                                        <li><a href="join.php"><i class="fas fa-chevron-left"></i> انضم إلينا</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="footer-links">
                                    <h5 class="links-title"><i class="fas fa-cogs"></i> خدماتنا</h5>
                                    <ul>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم والتسويق</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> المواقع الإلكترونية والتطبيقات</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> التصميم الداخلي والخارجي</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> الدروس الخصوصية</a></li>
                                        <li><a href="services.php"><i class="fas fa-chevron-left"></i> وغيرها</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-cta">
                            <h5 class="cta-title"><i class="fas fa-comments"></i> تواصل معنا</h5>
                            <p class="cta-desc">نسعد بالإجابة على استفساراتك</p>
                            <a href="https://wa.me/96892332749" class="whatsapp-btn" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i><span>تواصل عبر واتساب</span></a>
                            <div class="social-icons">
                                <span class="social-label">تابعنا على</span>
                                <div class="icons-row">
                                    <a href="https://instagram.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon instagram" title="انستقرام"><i class="fab fa-instagram"></i></a>
                                    <a href="https://linkedin.com/company/exp-edu" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" title="لينكدإن"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://youtube.com/@exp_edu" target="_blank" rel="noopener noreferrer" class="social-icon youtube" title="يوتيوب"><i class="fab fa-youtube"></i></a>
                                    <a href="https://x.com/exp_edu_" target="_blank" rel="noopener noreferrer" class="social-icon twitter x-social" title="إكس" aria-label="إكس"><span class="x-social-mark">X</span></a>
                                </div>
                            </div>
                            <div class="newsletter-mini">
                                <span>اشترك في النشرة البريدية</span>
                                <div class="newsletter-form">
                                    <input type="email" placeholder="بريدك الإلكتروني" aria-label="البريد الإلكتروني">
                                    <button type="button" aria-label="اشتراك"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-content">
                    <div class="copyright"><span>© <?= date('Y') ?> بوابة خبرة</span><span class="separator">|</span><span>جميع الحقوق محفوظة</span></div>
                    <div class="bottom-links"><a href="#">سياسة الخصوصية</a><a href="#">الشروط والأحكام</a><a href="index.php#contact">تواصل معنا</a></div>
                    <div class="made-with"><span>صُنع بـ</span><i class="fas fa-heart"></i><span>في عُمان</span></div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Modal: تسجيل كخبير -->
    <div class="modal fade" id="expertModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-graduate"></i>
                        التسجيل كخبير
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="request_type" value="expert">
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                الاسم الكامل
                            </label>
                            <input type="text" name="full_name" class="form-control" required 
                                   placeholder="أدخل اسمك الكامل">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="email" class="form-control" required 
                                       placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    رقم الهاتف
                                </label>
                                <input type="tel" name="phone" class="form-control" required 
                                       placeholder="+968 9XXXXXXX">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                التخصص / المجال
                            </label>
                            <input type="text" name="specialization" class="form-control" required 
                                   placeholder="مثال: تطوير البرمجيات، إدارة الأعمال">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                نبذة قصيرة عن خبرتك
                            </label>
                            <textarea name="experience_description" class="form-control" required 
                                      placeholder="اكتب نبذة عن خبرتك ومهاراتك..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                رفع السيرة الذاتية (اختياري)
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="cv_file" accept=".pdf,.doc,.docx" id="expertCV">
                                <label for="expertCV" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    اختر ملف (PDF, DOC, DOCX)
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            سجّل كخبير
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: تسجيل كمشرف -->
    <div class="modal fade" id="supervisorModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-chalkboard-teacher"></i>
                        التسجيل كمشرف
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="request_type" value="supervisor">
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                الاسم الكامل
                            </label>
                            <input type="text" name="full_name" class="form-control" required 
                                   placeholder="أدخل اسمك الكامل">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="email" class="form-control" required 
                                       placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    رقم الهاتف
                                </label>
                                <input type="tel" name="phone" class="form-control" required 
                                       placeholder="+968 9XXXXXXX">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                المجال/التخصص الذي ترغب في الإشراف عليه
                            </label>
                            <input type="text" name="supervision_field" class="form-control" required 
                                   placeholder="مثال: التسويق الرقمي، البرمجة">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                خبرة إشرافية سابقة (اختياري)
                            </label>
                            <textarea name="previous_supervision_experience" class="form-control" 
                                      placeholder="اذكر خبراتك الإشرافية السابقة إن وجدت..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            سجّل كمشرف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: تسجيل كمؤسسة -->
    <div class="modal fade" id="institutionModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-building"></i>
                        التسجيل كمؤسسة/شركة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="request_type" value="institution">
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                اسم المؤسسة
                            </label>
                            <input type="text" name="institution_name" class="form-control" required 
                                   placeholder="أدخل اسم المؤسسة أو الشركة">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                الشخص المسؤول (الاسم الكامل)
                            </label>
                            <input type="text" name="full_name" class="form-control" required 
                                   placeholder="اسم الشخص المسؤول عن التواصل">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="email" class="form-control" required 
                                       placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    رقم الهاتف
                                </label>
                                <input type="tel" name="phone" class="form-control" required 
                                       placeholder="+968 9XXXXXXX">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                نوع المؤسسة
                            </label>
                            <select name="institution_type" class="form-select" required>
                                <option value="">اختر نوع المؤسسة</option>
                                <option value="company">شركة</option>
                                <option value="government">جهة حكومية</option>
                                <option value="nonprofit">منظمة غير ربحية</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                الخدمات المطلوبة / أهداف التعاون
                            </label>
                            <textarea name="services_required" class="form-control" required 
                                      placeholder="اشرح الخدمات التي تحتاجها أو أهداف التعاون..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            تعاون معنا
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: تسجيل كمتعلم -->
    <div class="modal fade" id="learnerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-book-reader"></i>
                        التسجيل كمتعلم/طالب
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="request_type" value="learner">
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <span class="required">*</span>
                                الاسم الكامل
                            </label>
                            <input type="text" name="full_name" class="form-control" required 
                                   placeholder="أدخل اسمك الكامل">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" name="email" class="form-control" required 
                                       placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    رقم الهاتف
                                </label>
                                <input type="tel" name="phone" class="form-control" required 
                                       placeholder="+968 9XXXXXXX">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    اختر الفئة
                                </label>
                                <select name="learner_category" class="form-select" required>
                                    <option value="">اختر فئتك</option>
                                    <option value="student">طالب</option>
                                    <option value="graduate">خريج</option>
                                    <option value="employee">موظف</option>
                                    <option value="job_seeker">باحث عن عمل</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="required">*</span>
                                    المجال الذي ترغب في التعلم فيه
                                </label>
                                <input type="text" name="learning_field" class="form-control" required 
                                       placeholder="مثال: البرمجة، التسويق">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                التخصص / المهارات المطلوبة
                            </label>
                            <textarea name="skills_required" class="form-control" 
                                      placeholder="اذكر المهارات أو التخصصات التي تريد تعلمها..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                هل ترغب في خدمة محددة؟
                            </label>
                            <select name="specific_service" class="form-select">
                                <option value="">اختر خدمة (اختياري)</option>
                                <option value="individual_courses">دورات فردية</option>
                                <option value="training_programs">برامج تدريبية</option>
                                <option value="consultation">استشارات مهنية</option>
                                <option value="career_guidance">توجيه مهني</option>
                                <option value="cv_preparation">إعداد السيرة الذاتية</option>
                                <option value="interview_prep">التحضير للمقابلات</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            ابدأ الآن
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تحديث نص رفع الملف
        document.getElementById('expertCV')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('label[for="expertCV"]');
                label.innerHTML = '<i class="fas fa-check-circle"></i> تم اختيار: ' + fileName;
                label.style.borderColor = '#28a745';
                label.style.background = '#d4edda';
                label.style.color = '#28a745';
            }
        });
        
        // إخفاء الـ alerts تلقائياً بعد 5 ثوانٍ
        setTimeout(() => {
            document.querySelectorAll('.alert-custom').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // إغلاق الـ modal بعد الإرسال الناجح
        <?php if ($success): ?>
            document.querySelectorAll('.modal').forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        <?php endif; ?>
    </script>
</body>
</html>
