<?php
/**
 * إدارة الأسئلة الشائعة
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

// إنشاء الجدول إذا لم يكن موجوداً
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS faq (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question VARCHAR(500) NOT NULL,
            answer TEXT NOT NULL,
            category VARCHAR(100) DEFAULT 'عام',
            display_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
} catch (Exception $e) {
    // الجدول موجود
}

// معالجة الإضافة/التعديل/الحذف
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO faq (question, answer, category, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['question'],
                $_POST['answer'],
                $_POST['category'],
                $_POST['display_order']
            ]);
            $success = "تم إضافة السؤال بنجاح";
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE faq SET question=?, answer=?, category=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([
                $_POST['question'],
                $_POST['answer'],
                $_POST['category'],
                $_POST['display_order'],
                isset($_POST['is_active']) ? 1 : 0,
                $_POST['id']
            ]);
            $success = "تم تحديث السؤال بنجاح";
        } elseif (isset($_POST['delete'])) {
            $pdo->prepare("DELETE FROM faq WHERE id=?")->execute([$_POST['id']]);
            $success = "تم حذف السؤال";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$faqs = $pdo->query("SELECT * FROM faq ORDER BY display_order, id")->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM faq ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

$me = current_user();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأسئلة الشائعة - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0 !important; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
        .faq-item { background: white; border-radius: 10px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .faq-item.inactive { opacity: 0.6; }
        .category-badge { font-size: 0.75rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة التحكم
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-question-circle me-2"></i>إدارة الأسئلة الشائعة
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

        <div class="row">
            <!-- نموذج الإضافة -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-plus-circle me-2"></i>إضافة سؤال جديد
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">السؤال</label>
                                <textarea name="question" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الإجابة</label>
                                <textarea name="answer" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التصنيف</label>
                                <input type="text" name="category" class="form-control" value="عام" list="categories">
                                <datalist id="categories">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الترتيب</label>
                                <input type="number" name="display_order" class="form-control" value="0">
                            </div>
                            <button type="submit" name="add" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>إضافة السؤال
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body text-center">
                        <a href="../public/faq.php" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-1"></i>معاينة صفحة الأسئلة
                        </a>
                    </div>
                </div>
            </div>

            <!-- قائمة الأسئلة -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i>الأسئلة الشائعة (<?= count($faqs) ?>)</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($faqs)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-question-circle fa-3x mb-3"></i>
                                <p>لا توجد أسئلة بعد. أضف سؤالك الأول!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($faqs as $faq): ?>
                                <div class="faq-item <?= $faq['is_active'] ? '' : 'inactive' ?>">
                                    <form method="POST" class="faq-form">
                                        <input type="hidden" name="id" value="<?= $faq['id'] ?>">
                                        
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-info category-badge"><?= htmlspecialchars($faq['category']) ?></span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" <?= $faq['is_active'] ? 'checked' : '' ?>>
                                                <label class="form-check-label small">نشط</label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small text-muted mb-1">السؤال:</label>
                                            <textarea name="question" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($faq['question']) ?></textarea>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small text-muted mb-1">الإجابة:</label>
                                            <textarea name="answer" class="form-control form-control-sm" rows="3"><?= htmlspecialchars($faq['answer']) ?></textarea>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <input type="text" name="category" class="form-control form-control-sm" value="<?= htmlspecialchars($faq['category']) ?>" placeholder="التصنيف">
                                            </div>
                                            <div class="col-6">
                                                <input type="number" name="display_order" class="form-control form-control-sm" value="<?= $faq['display_order'] ?>" placeholder="الترتيب">
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="update" class="btn btn-sm btn-success">
                                                <i class="fas fa-save me-1"></i>حفظ
                                            </button>
                                            <button type="submit" name="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                <i class="fas fa-trash me-1"></i>حذف
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


