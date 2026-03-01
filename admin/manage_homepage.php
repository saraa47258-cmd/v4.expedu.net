<?php
/**
 * إدارة الصفحة الرئيسية
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

// جلب إعدادات الصفحة الرئيسية من قاعدة البيانات
try {
    $settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
} catch (Exception $e) {
    // إنشاء جدول الإعدادات إذا لم يكن موجوداً
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            id INT PRIMARY KEY DEFAULT 1,
            hero_title VARCHAR(255) DEFAULT 'بوابة خبرة ',
            hero_subtitle VARCHAR(255) DEFAULT 'اصنع طريقك للنجاح',
            hero_description TEXT,
            hero_button_text VARCHAR(100) DEFAULT 'ابدأ رحلتك ',
            hero_button_url VARCHAR(255) DEFAULT '#courses',
            stats_students INT DEFAULT 3000,
            stats_rating DECIMAL(2,1) DEFAULT 4.9,
            contact_whatsapp VARCHAR(50),
            contact_email VARCHAR(100),
            contact_phone VARCHAR(50),
            social_instagram VARCHAR(255),
            social_twitter VARCHAR(255),
            social_linkedin VARCHAR(255),
            social_youtube VARCHAR(255),
            footer_text TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    $pdo->exec("INSERT IGNORE INTO site_settings (id) VALUES (1)");
    $settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
}

// معالجة التحديث
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE site_settings SET
                hero_title = ?,
                hero_subtitle = ?,
                hero_description = ?,
                hero_button_text = ?,
                hero_button_url = ?,
                stats_students = ?,
                stats_rating = ?,
                contact_whatsapp = ?,
                contact_email = ?,
                contact_phone = ?,
                social_instagram = ?,
                social_twitter = ?,
                social_linkedin = ?,
                social_youtube = ?,
                footer_text = ?
            WHERE id = 1
        ");
        $stmt->execute([
            $_POST['hero_title'],
            $_POST['hero_subtitle'],
            $_POST['hero_description'],
            $_POST['hero_button_text'],
            $_POST['hero_button_url'],
            $_POST['stats_students'],
            $_POST['stats_rating'],
            $_POST['contact_whatsapp'],
            $_POST['contact_email'],
            $_POST['contact_phone'],
            $_POST['social_instagram'],
            $_POST['social_twitter'],
            $_POST['social_linkedin'],
            $_POST['social_youtube'],
            $_POST['footer_text']
        ]);
        $success = "تم تحديث إعدادات الصفحة الرئيسية بنجاح";
        $settings = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
    } catch (Exception $e) {
        $error = "حدث خطأ: " . $e->getMessage();
    }
}

$me = current_user();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الصفحة الرئيسية - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0 !important; }
        .form-label { font-weight: 600; color: #333; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .section-title { background: #f8f9fa; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; color: #667eea; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-home me-2"></i>إدارة الصفحة الرئيسية
            </span>
        </div>
    </nav>

    <div class="container pb-5">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <!-- قسم Hero -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-image me-2"></i>قسم الترحيب (Hero)
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">العنوان الرئيسي</label>
                                <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">العنوان الفرعي</label>
                                <input type="text" name="hero_subtitle" class="form-control" value="<?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea name="hero_description" class="form-control" rows="3"><?= htmlspecialchars($settings['hero_description'] ?? '') ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نص الزر</label>
                                    <input type="text" name="hero_button_text" class="form-control" value="<?= htmlspecialchars($settings['hero_button_text'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رابط الزر</label>
                                    <input type="text" name="hero_button_url" class="form-control" value="<?= htmlspecialchars($settings['hero_button_url'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم التواصل -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-address-book me-2"></i>معلومات التواصل
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fab fa-whatsapp text-success me-1"></i>واتساب</label>
                                    <input type="text" name="contact_whatsapp" class="form-control" value="<?= htmlspecialchars($settings['contact_whatsapp'] ?? '') ?>" placeholder="+968xxxxxxxx">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fas fa-envelope text-primary me-1"></i>البريد الإلكتروني</label>
                                    <input type="email" name="contact_email" class="form-control" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fas fa-phone text-info me-1"></i>الهاتف</label>
                                    <input type="text" name="contact_phone" class="form-control" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- التذييل -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-file-alt me-2"></i>نص التذييل
                        </div>
                        <div class="card-body">
                            <textarea name="footer_text" class="form-control" rows="3"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- الشريط الجانبي -->
                <div class="col-lg-4">
                    <!-- الإحصائيات -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عدد الطلاب</label>
                                <input type="number" name="stats_students" class="form-control" value="<?= $settings['stats_students'] ?? 3000 ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التقييم</label>
                                <input type="number" step="0.1" min="0" max="5" name="stats_rating" class="form-control" value="<?= $settings['stats_rating'] ?? 4.9 ?>">
                            </div>
                        </div>
                    </div>

                    <!-- وسائل التواصل -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-share-alt me-2"></i>وسائل التواصل الاجتماعي
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger me-1"></i>انستغرام</label>
                                <input type="url" name="social_instagram" class="form-control" value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-twitter text-info me-1"></i>تويتر</label>
                                <input type="url" name="social_twitter" class="form-control" value="<?= htmlspecialchars($settings['social_twitter'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-linkedin text-primary me-1"></i>لينكد إن</label>
                                <input type="url" name="social_linkedin" class="form-control" value="<?= htmlspecialchars($settings['social_linkedin'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-youtube text-danger me-1"></i>يوتيوب</label>
                                <input type="url" name="social_youtube" class="form-control" value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="../public/index.php" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-1"></i>معاينة الصفحة
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


