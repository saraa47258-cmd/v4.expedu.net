<?php
// admin/upload.php
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// يسمح للأدمن والمشرف فقط برفع الكورسات
require_role(['admin', 'instructor']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    try {
        $pdo = getPDO();
        $title = trim($_POST['title'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $instructor_id = $_SESSION['user_id'];

        // التحقق من البيانات المطلوبة
        if(empty($title)) {
            throw new Exception('عنوان الكورس مطلوب');
        }

        $slug = slugify($title) . '-' . time();
        
        // تسجيل بداية العملية
        error_log("Starting course upload: " . $title);

        // رفع صورة الغلاف
        $thumbnailUrl = null;
        $thumbTmp = $_FILES['thumbnail'] ?? null;
        if($thumbTmp && $thumbTmp['error'] === UPLOAD_ERR_OK){
            error_log("Uploading thumbnail: " . $thumbTmp['name']);
            $localPath = $thumbTmp['tmp_name'];
            $ext = pathinfo($thumbTmp['name'], PATHINFO_EXTENSION);
            $key = "thumbnails/{$slug}." . $ext;
            $uploaded = uploadFileToWasabi($localPath, $key, $thumbTmp['type']);
            if($uploaded) {
                $thumbnailUrl = $uploaded;
                error_log("Thumbnail uploaded successfully: " . $uploaded);
            } else {
                error_log("Failed to upload thumbnail");
            }
        } else {
            error_log("Thumbnail upload error: " . ($thumbTmp['error'] ?? 'No file'));
        }

        // إنشاء كورس أولاً
        error_log("Creating course in database");
        $stmt = $pdo->prepare("INSERT INTO courses (instructor_id, category_id, title, slug, short_description, description, price, thumbnail_url) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$instructor_id, $category_id, $title, $slug, $short_description, $description, $price, $thumbnailUrl]);
        $courseId = $pdo->lastInsertId();
        error_log("Course created with ID: " . $courseId);

        // رفع دروس متعددة
        $lessonCounter = 1;
        $uploadedLessons = 0;
        
        // معالجة الفيديوهات المتعددة
        if(isset($_FILES['videos']) && is_array($_FILES['videos']['name'])){
            $videoCount = count($_FILES['videos']['name']);
            error_log("Processing {$videoCount} multiple videos");
            
            for($i = 0; $i < $videoCount; $i++){
                if($_FILES['videos']['error'][$i] === UPLOAD_ERR_OK){
                    error_log("Uploading video {$i}: " . $_FILES['videos']['name'][$i]);
                    $localPath = $_FILES['videos']['tmp_name'][$i];
                    $fileName = $_FILES['videos']['name'][$i];
                    $fileType = $_FILES['videos']['type'][$i];
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                    
                    // إنشاء مفتاح فريد للدرس
                    $lessonKey = "courses/{$slug}/lesson{$lessonCounter}." . $ext;
                    
                    // رفع الملف إلى Wasabi
                    $uploaded = uploadFileToWasabi($localPath, $lessonKey, $fileType);
                    
                    if($uploaded){
                        // الحصول على عنوان الدرس من النموذج أو استخدام اسم الملف
                        $lessonTitle = $_POST['lesson_titles'][$i] ?? "الدرس {$lessonCounter}";
                        $lessonDesc = $_POST['lesson_descriptions'][$i] ?? '';
                        
                        // إدراج الدرس في قاعدة البيانات
                        $stmt = $pdo->prepare("INSERT INTO lessons (course_id, title, description, wasabi_key) VALUES (?,?,?,?)");
                        $stmt->execute([$courseId, $lessonTitle, $lessonDesc, $lessonKey]);
                        
                        error_log("Lesson {$lessonCounter} uploaded successfully");
                        $lessonCounter++;
                        $uploadedLessons++;
                    } else {
                        error_log("Failed to upload video {$i}");
                    }
                } else {
                    error_log("Video {$i} upload error: " . $_FILES['videos']['error'][$i]);
                }
            }
        }
        
        // معالجة الفيديو الواحد (للتوافق مع النسخة القديمة)
        $videoTmp = $_FILES['video'] ?? null;
        if($videoTmp && $videoTmp['error'] === UPLOAD_ERR_OK){
            error_log("Uploading single video: " . $videoTmp['name']);
            $localPath = $videoTmp['tmp_name'];
            $ext = pathinfo($videoTmp['name'], PATHINFO_EXTENSION);
            $lessonKey = "courses/{$slug}/lesson{$lessonCounter}." . $ext;
            $uploaded = uploadFileToWasabi($localPath, $lessonKey, $videoTmp['type']);
            if($uploaded){
                $lessonTitle = $_POST['single_lesson_title'] ?? "الدرس {$lessonCounter}";
                $lessonDesc = $_POST['single_lesson_description'] ?? '';
                $stmt = $pdo->prepare("INSERT INTO lessons (course_id, title, description, wasabi_key) VALUES (?,?,?,?)");
                $stmt->execute([$courseId, $lessonTitle, $lessonDesc, $lessonKey]);
                error_log("Single lesson uploaded successfully");
                $uploadedLessons++;
            } else {
                error_log("Failed to upload single video");
            }
        }

        // التحقق من رفع الدروس
        if($uploadedLessons === 0) {
            throw new Exception('لم يتم رفع أي فيديو. يرجى المحاولة مرة أخرى.');
        }

        error_log("Course upload completed successfully. Lessons uploaded: {$uploadedLessons}");
        header('Location: dashboard.php?msg=uploaded&lessons=' . $uploadedLessons);
        exit;
        
    } catch (Exception $e) {
        error_log("Course upload error: " . $e->getMessage());
        $errorMessage = $e->getMessage();
        // يمكن إضافة رسالة خطأ للعرض للمستخدم
    }
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>رفع كورس جديد - ExpEdu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="../public/assets/css/responsive-enhancements.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6366f1;
      --primary-dark: #4f46e5;
      --primary-light: #8b5cf6;
      --secondary-color: #64748b;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --warning-color: #f59e0b;
      --info-color: #06b6d4;
      --light-color: #f8fafc;
      --dark-color: #1e293b;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-600: #4b5563;
      --gray-700: #374151;
      --gray-800: #1f2937;
      --gray-900: #111827;
    }
    
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
      line-height: 1.6;
      color: var(--gray-700);
    }
    
    /* Enhanced Navbar */
    .navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--gray-200);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
      font-weight: 800;
      font-size: 1.8rem;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
      transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover {
      transform: scale(1.05);
    }
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #6366f1 100%);
      position: relative;
      overflow: hidden;
      padding: 60px 0;
      margin-bottom: 50px;
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      color: white;
    }
    
    .hero-title {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 20px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      line-height: 1.1;
    }
    
    .hero-subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
      margin-bottom: 30px;
      color: rgba(255, 255, 255, 0.9);
    }
    
    /* Form Container */
    .form-container {
      background: white;
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: 1px solid var(--gray-100);
      position: relative;
      overflow: hidden;
    }
    
    .form-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    }
    
    .form-title {
      font-size: 2rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--gray-800), var(--gray-600));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 30px;
      text-align: center;
      position: relative;
    }
    
    .form-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
      border-radius: 2px;
    }
    
    /* Form Groups */
    .form-group {
      margin-bottom: 30px;
      position: relative;
    }
    
    .form-label {
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 12px;
      display: block;
      font-size: 1rem;
    }
    
    .form-label i {
      margin-left: 8px;
      color: var(--primary-color);
    }
    
    .form-control {
      border-radius: 12px;
      border: 2px solid var(--gray-200);
      padding: 16px 20px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
      width: 100%;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
      transform: translateY(-2px);
      outline: none;
    }
    
    .form-control::placeholder {
      color: var(--gray-400);
    }
    
    /* File Upload Styling */
    .file-upload {
      position: relative;
      display: inline-block;
      width: 100%;
    }
    
    .file-upload input[type="file"] {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }
    
    .file-upload-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      border: 2px dashed var(--gray-300);
      border-radius: 12px;
      background: var(--gray-50);
      cursor: pointer;
      transition: all 0.3s ease;
      min-height: 120px;
      flex-direction: column;
      gap: 10px;
    }
    
    .file-upload-label:hover {
      border-color: var(--primary-color);
      background: rgba(99, 102, 241, 0.05);
      transform: translateY(-2px);
    }
    
    .file-upload-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 10px;
    }
    
    .file-upload-text {
      font-weight: 600;
      color: var(--gray-700);
      text-align: center;
    }
    
    .file-upload-hint {
      font-size: 0.9rem;
      color: var(--gray-500);
      text-align: center;
    }
    
    /* Enhanced Buttons */
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      border: none;
      border-radius: 12px;
      font-weight: 600;
      padding: 16px 32px;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.3s;
    }
    
    .btn-primary:hover::before {
      left: 100%;
    }
    
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }
    
    .btn-secondary {
      background: var(--gray-100);
      border: 2px solid var(--gray-300);
      border-radius: 12px;
      font-weight: 600;
      padding: 16px 32px;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      color: var(--gray-700);
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }
    
    .btn-secondary:hover {
      background: var(--gray-200);
      border-color: var(--gray-400);
      transform: translateY(-2px);
      color: var(--gray-800);
    }
    
    /* Form Steps */
    .form-steps {
      display: flex;
      justify-content: center;
      margin-bottom: 40px;
      gap: 20px;
    }
    
    .step {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      border-radius: 25px;
      background: var(--gray-100);
      color: var(--gray-600);
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .step.active {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      color: white;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .step-number {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.2rem;
      }
      
      .form-container {
        padding: 30px 20px;
        margin: 0 15px;
      }
      
      .form-steps {
        flex-direction: column;
        gap: 10px;
      }
      
      .step {
        justify-content: center;
      }
    }
    
    /* Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .form-container {
      animation: fadeInUp 0.6s ease-out;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: var(--gray-100);
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }
  </style>
</head>
<body>

<!-- Enhanced Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-graduation-cap me-2"></i>ExpEdu
    </a>
    <div class="ms-auto">
      <a class="btn btn-secondary me-2" href="dashboard.php">
        <i class="fas fa-arrow-right me-1"></i>العودة للوحة التحكم
      </a>
      <a class="btn btn-secondary" href="index.php">
        <i class="fas fa-home me-1"></i>الرئيسية
      </a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
  <div class="container">
    <div class="hero-content">
      <h1 class="hero-title">إنشاء كورس جديد</h1>
      <p class="hero-subtitle">شارك معرفتك مع العالم وأنشئ محتوى تعليمي احترافي</p>
    </div>
  </div>
</div>

        <!-- Main Content -->
        <div class="container pb-5">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              
              <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  <strong>خطأ في الرفع:</strong> <?= htmlspecialchars($errorMessage) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>
              
              <div class="form-container">
        <h2 class="form-title">معلومات الكورس</h2>
        
        <!-- Form Steps -->
        <div class="form-steps">
          <div class="step active">
            <div class="step-number">1</div>
            <span>معلومات أساسية</span>
          </div>
          <div class="step">
            <div class="step-number">2</div>
            <span>الملفات</span>
          </div>
          <div class="step">
            <div class="step-number">3</div>
            <span>النشر</span>
          </div>
        </div>
        
        <form method="post" enctype="multipart/form-data">
          <!-- Basic Information -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-heading"></i>عنوان الكورس
            </label>
            <input required name="title" class="form-control" placeholder="أدخل عنوان الكورس الجذاب" />
          </div>
          
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-align-right"></i>وصف قصير
            </label>
            <input name="short_description" class="form-control" placeholder="وصف مختصر للكورس (سيظهر في البطاقة)" />
          </div>
          
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-file-alt"></i>التفاصيل الكاملة
            </label>
            <textarea name="description" class="form-control" rows="5" placeholder="وصف مفصل للكورس ومحتوياته"></textarea>
          </div>
          
          <!-- File Uploads -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-image"></i>صورة الغلاف
            </label>
            <div class="file-upload">
              <input type="file" name="thumbnail" accept="image/*" required />
              <label class="file-upload-label">
                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                <div class="file-upload-text">اختر صورة الغلاف</div>
                <div class="file-upload-hint">JPG, PNG, GIF - الحد الأقصى 5MB</div>
              </label>
            </div>
          </div>
          
          <!-- Multiple Videos Upload -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-video"></i>فيديوهات الكورس (متعددة)
            </label>
            <div class="file-upload">
              <input type="file" name="videos[]" accept="video/*" multiple />
              <label class="file-upload-label">
                <i class="fas fa-video file-upload-icon"></i>
                <div class="file-upload-text">اختر عدة ملفات فيديو</div>
                <div class="file-upload-hint">MP4, AVI, MOV - يمكن اختيار عدة ملفات</div>
              </label>
            </div>
            
            <!-- Video Details Container -->
            <div id="video-details-container" class="mt-3" style="display: none;">
              <h6 class="text-primary mb-3">
                <i class="fas fa-list me-2"></i>تفاصيل الفيديوهات
              </h6>
              <div id="video-details-list"></div>
            </div>
          </div>
          
          <!-- Single Video Upload (Fallback) -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-video"></i>فيديو واحد (اختياري)
            </label>
            <div class="file-upload">
              <input type="file" name="video" accept="video/*" />
              <label class="file-upload-label">
                <i class="fas fa-video file-upload-icon"></i>
                <div class="file-upload-text">أو اختر فيديو واحد</div>
                <div class="file-upload-hint">MP4, AVI, MOV - الحد الأقصى 500MB</div>
              </label>
            </div>
            
            <!-- Single Video Details -->
            <div class="row g-3 mt-3" id="single-video-details" style="display: none;">
              <div class="col-md-6">
                <label class="form-label">
                  <i class="fas fa-heading"></i>عنوان الدرس
                </label>
                <input type="text" name="single_lesson_title" class="form-control" placeholder="عنوان الدرس" />
              </div>
              <div class="col-md-6">
                <label class="form-label">
                  <i class="fas fa-align-right"></i>وصف الدرس
                </label>
                <input type="text" name="single_lesson_description" class="form-control" placeholder="وصف مختصر" />
              </div>
            </div>
          </div>
          
          <!-- Pricing -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-tag"></i>السعر (ريال)
            </label>
            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" />
          </div>
          
          <!-- Submit Button -->
          <div class="form-group mt-5">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-rocket me-2"></i>رفع ونشر الكورس
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Enhanced File Upload Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Multiple videos upload handling
  const multipleVideosInput = document.querySelector('input[name="videos[]"]');
  const singleVideoInput = document.querySelector('input[name="video"]');
  const videoDetailsContainer = document.getElementById('video-details-container');
  const videoDetailsList = document.getElementById('video-details-list');
  const singleVideoDetails = document.getElementById('single-video-details');
  
  // Handle multiple videos selection
  multipleVideosInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const label = this.nextElementSibling;
    const text = label.querySelector('.file-upload-text');
    const hint = label.querySelector('.file-upload-hint');
    
    if (files.length > 0) {
      text.textContent = `تم اختيار ${files.length} ملف فيديو`;
      hint.textContent = files.map(f => `${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB)`).join(', ');
      label.style.borderColor = 'var(--success-color)';
      label.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
      
      // Show video details form
      videoDetailsContainer.style.display = 'block';
      videoDetailsList.innerHTML = '';
      
      files.forEach((file, index) => {
        const videoDetailDiv = document.createElement('div');
        videoDetailDiv.className = 'card mb-3';
        videoDetailDiv.innerHTML = `
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">
                  <i class="fas fa-play-circle me-1"></i>الملف ${index + 1}
                </label>
                <div class="form-control-plaintext">
                  <small class="text-muted">${file.name}</small>
                  <br><small class="text-info">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
              </div>
              <div class="col-md-4">
                <label class="form-label">عنوان الدرس</label>
                <input type="text" name="lesson_titles[]" class="form-control" 
                       placeholder="عنوان الدرس ${index + 1}" 
                       value="الدرس ${index + 1}" />
              </div>
              <div class="col-md-4">
                <label class="form-label">وصف الدرس</label>
                <input type="text" name="lesson_descriptions[]" class="form-control" 
                       placeholder="وصف مختصر للدرس" />
              </div>
            </div>
          </div>
        `;
        videoDetailsList.appendChild(videoDetailDiv);
      });
      
      // Hide single video details
      singleVideoDetails.style.display = 'none';
    } else {
      videoDetailsContainer.style.display = 'none';
    }
  });
  
  // Handle single video selection
  singleVideoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const label = this.nextElementSibling;
    const text = label.querySelector('.file-upload-text');
    const hint = label.querySelector('.file-upload-hint');
    
    if (file) {
      text.textContent = file.name;
      hint.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
      label.style.borderColor = 'var(--success-color)';
      label.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
      
      // Show single video details
      singleVideoDetails.style.display = 'block';
      
      // Hide multiple videos details
      videoDetailsContainer.style.display = 'none';
    } else {
      singleVideoDetails.style.display = 'none';
    }
  });
  
  // Handle other file inputs (thumbnail)
  const otherFileInputs = document.querySelectorAll('input[type="file"]:not([name="videos[]"]):not([name="video"])');
  
  otherFileInputs.forEach(input => {
    input.addEventListener('change', function(e) {
      const file = e.target.files[0];
      const label = this.nextElementSibling;
      const text = label.querySelector('.file-upload-text');
      const hint = label.querySelector('.file-upload-hint');
      
      if (file) {
        text.textContent = file.name;
        hint.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
        label.style.borderColor = 'var(--success-color)';
        label.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
      }
    });
  });
  
  // Form validation
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    // Check if at least one video is selected
    const multipleVideos = multipleVideosInput.files.length > 0;
    const singleVideo = singleVideoInput.files.length > 0;
    
    if (!multipleVideos && !singleVideo) {
      alert('يرجى اختيار فيديو واحد على الأقل');
      isValid = false;
    }
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--danger-color)';
        isValid = false;
      } else {
        field.style.borderColor = 'var(--success-color)';
      }
    });
    
    if (!isValid) {
      e.preventDefault();
      return false;
    }
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';
    submitBtn.disabled = true;
    
    // Re-enable button after 10 seconds (in case of error)
    setTimeout(() => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }, 10000);
  });
  
  // Real-time validation
  const inputs = form.querySelectorAll('input, textarea');
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      if (this.value.trim()) {
        this.style.borderColor = 'var(--success-color)';
      } else {
        this.style.borderColor = 'var(--gray-200)';
      }
    });
  });
  
  // Add drag and drop functionality
  const fileUploadLabels = document.querySelectorAll('.file-upload-label');
  
  fileUploadLabels.forEach(label => {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      label.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
      label.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
      label.addEventListener(eventName, unhighlight, false);
    });
    
    label.addEventListener('drop', handleDrop, false);
  });
  
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  
  function highlight(e) {
    e.currentTarget.style.borderColor = 'var(--primary-color)';
    e.currentTarget.style.backgroundColor = 'rgba(99, 102, 241, 0.1)';
  }
  
  function unhighlight(e) {
    e.currentTarget.style.borderColor = 'var(--gray-300)';
    e.currentTarget.style.backgroundColor = 'var(--gray-50)';
  }
  
  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    const input = e.currentTarget.previousElementSibling;
    
    if (files.length > 0) {
      input.files = files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }
});
</script>

</body>
</html>
