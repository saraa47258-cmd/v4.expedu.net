<?php
/**
 * إدارة صفحة من نحن
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

// إنشاء الجدول إذا لم يكن موجوداً
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS about_content (
            id INT PRIMARY KEY DEFAULT 1,
            main_title VARCHAR(255) DEFAULT 'من نحن',
            main_description TEXT,
            vision_title VARCHAR(255) DEFAULT 'رؤيتنا',
            vision_text TEXT,
            mission_title VARCHAR(255) DEFAULT 'رسالتنا',
            mission_text TEXT,
            values_title VARCHAR(255) DEFAULT 'قيمنا',
            values_text TEXT,
            goals_title VARCHAR(255) DEFAULT 'أهدافنا',
            goals_text TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    $pdo->exec("INSERT IGNORE INTO about_content (id) VALUES (1)");
} catch (Exception $e) {
    // الجدول موجود
}

$content = $pdo->query("SELECT * FROM about_content WHERE id = 1")->fetch();

// معالجة التحديث
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE about_content SET
                main_title = ?,
                main_description = ?,
                vision_title = ?,
                vision_text = ?,
                mission_title = ?,
                mission_text = ?,
                values_title = ?,
                values_text = ?,
                goals_title = ?,
                goals_text = ?
            WHERE id = 1
        ");
        $stmt->execute([
            $_POST['main_title'],
            $_POST['main_description'],
            $_POST['vision_title'],
            $_POST['vision_text'],
            $_POST['mission_title'],
            $_POST['mission_text'],
            $_POST['values_title'],
            $_POST['values_text'],
            $_POST['goals_title'],
            $_POST['goals_text']
        ]);
        $success = "تم تحديث محتوى صفحة من نحن بنجاح";
        $content = $pdo->query("SELECT * FROM about_content WHERE id = 1")->fetch();
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
    <title>إدارة صفحة من نحن - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0 !important; }
        .form-label { font-weight: 600; color: #333; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-info-circle me-2"></i>إدارة صفحة من نحن
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
            <!-- المحتوى الرئيسي -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-building me-2"></i>المحتوى الرئيسي
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">العنوان الرئيسي</label>
                        <input type="text" name="main_title" class="form-control" value="<?= htmlspecialchars($content['main_title'] ?? 'من نحن') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف الرئيسي</label>
                        <textarea name="main_description" class="form-control" rows="4"><?= htmlspecialchars($content['main_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- الرؤية -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-eye me-2"></i>الرؤية
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عنوان الرؤية</label>
                                <input type="text" name="vision_title" class="form-control" value="<?= htmlspecialchars($content['vision_title'] ?? 'رؤيتنا') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نص الرؤية</label>
                                <textarea name="vision_text" class="form-control" rows="4"><?= htmlspecialchars($content['vision_text'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الرسالة -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bullseye me-2"></i>الرسالة
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عنوان الرسالة</label>
                                <input type="text" name="mission_title" class="form-control" value="<?= htmlspecialchars($content['mission_title'] ?? 'رسالتنا') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نص الرسالة</label>
                                <textarea name="mission_text" class="form-control" rows="4"><?= htmlspecialchars($content['mission_text'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- القيم -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-gem me-2"></i>القيم
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عنوان القيم</label>
                                <input type="text" name="values_title" class="form-control" value="<?= htmlspecialchars($content['values_title'] ?? 'قيمنا') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نص القيم</label>
                                <textarea name="values_text" class="form-control" rows="4"><?= htmlspecialchars($content['values_text'] ?? '') ?></textarea>
                                <small class="text-muted">يمكنك كتابة كل قيمة في سطر جديد</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الأهداف -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-flag me-2"></i>الأهداف
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عنوان الأهداف</label>
                                <input type="text" name="goals_title" class="form-control" value="<?= htmlspecialchars($content['goals_title'] ?? 'أهدافنا') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نص الأهداف</label>
                                <textarea name="goals_text" class="form-control" rows="4"><?= htmlspecialchars($content['goals_text'] ?? '') ?></textarea>
                                <small class="text-muted">يمكنك كتابة كل هدف في سطر جديد</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="d-flex gap-3 justify-content-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save me-2"></i>حفظ التغييرات
                </button>
                <a href="../public/about.php" target="_blank" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-external-link-alt me-1"></i>معاينة الصفحة
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


