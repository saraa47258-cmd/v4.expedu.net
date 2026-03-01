<?php
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO testimonials (name, position, company, testimonial, rating, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['position'], $_POST['company'], $_POST['testimonial'], $_POST['rating'], $_POST['display_order']]);
            $success = "تم إضافة الرأي بنجاح";
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE testimonials SET name=?, position=?, company=?, testimonial=?, rating=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([$_POST['name'], $_POST['position'], $_POST['company'], $_POST['testimonial'], $_POST['rating'], $_POST['display_order'], $_POST['is_active'], $_POST['id']]);
            $success = "تم تحديث الرأي";
        } elseif (isset($_POST['delete'])) {
            $pdo->prepare("DELETE FROM testimonials WHERE id=?")->execute([$_POST['id']]);
            $success = "تم الحذف";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY display_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة آراء العملاء - بوابة خبرة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #066755, #044a3d); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_new.php">
                <i class="fas fa-arrow-right me-2"></i>العودة
            </a>
            <h4 class="text-white mb-0"><i class="fas fa-star me-2"></i>إدارة آراء العملاء</h4>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-2"></i>إضافة رأي جديد
        </button>
        
        <div class="row g-3">
            <?php foreach ($testimonials as $test): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold"><?= htmlspecialchars($test['name']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($test['position']) ?></p>
                        <div class="mb-2">
                            <?php for($i=0; $i<$test['rating']; $i++): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars(substr($test['testimonial'], 0, 100)) ?>...</p>
                        <button class="btn btn-sm btn-primary" onclick='editTest(<?= json_encode($test) ?>)'>تعديل</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $test['id'] ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة رأي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>الاسم *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>المنصب</label>
                            <input type="text" name="position" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>الشركة</label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>الرأي *</label>
                            <textarea name="testimonial" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>التقييم</label>
                            <select name="rating" class="form-select">
                                <option value="5" selected>5 نجوم</option>
                                <option value="4">4 نجوم</option>
                                <option value="3">3 نجوم</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>الترتيب</label>
                            <input type="number" name="display_order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" name="add" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Modal: تعديل رأي -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الرأي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">الاسم *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">المنصب</label>
                            <input type="text" name="position" id="edit_position" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الشركة</label>
                            <input type="text" name="company" id="edit_company" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الرأي *</label>
                            <textarea name="testimonial" id="edit_testimonial" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">التقييم</label>
                            <select name="rating" id="edit_rating" class="form-select">
                                <option value="5">5 نجوم</option>
                                <option value="4">4 نجوم</option>
                                <option value="3">3 نجوم</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="display_order" id="edit_display_order" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" name="update" class="btn btn-primary">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function editTest(test) {
            document.getElementById('edit_id').value = test.id;
            document.getElementById('edit_name').value = test.name || '';
            document.getElementById('edit_position').value = test.position || '';
            document.getElementById('edit_company').value = test.company || '';
            document.getElementById('edit_testimonial').value = test.testimonial || '';
            document.getElementById('edit_rating').value = test.rating || 5;
            document.getElementById('edit_display_order').value = test.display_order || 0;
            document.getElementById('edit_is_active').value = test.is_active;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>


