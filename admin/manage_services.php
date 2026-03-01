<?php
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

// معالجة الإضافة/التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO services (specialization_id, name_ar, name_en, description, icon, price, target_audience, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['specialization_id'] ?: null,
                $_POST['name_ar'],
                $_POST['name_en'],
                $_POST['description'],
                $_POST['icon'],
                $_POST['price'],
                $_POST['target_audience'],
                $_POST['display_order']
            ]);
            $success = "تم إضافة الخدمة بنجاح";
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE services SET name_ar=?, name_en=?, description=?, icon=?, price=?, target_audience=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([
                $_POST['name_ar'],
                $_POST['name_en'],
                $_POST['description'],
                $_POST['icon'],
                $_POST['price'],
                $_POST['target_audience'],
                $_POST['display_order'],
                $_POST['is_active'],
                $_POST['id']
            ]);
            $success = "تم تحديث الخدمة";
        } elseif (isset($_POST['delete'])) {
            $pdo->prepare("DELETE FROM services WHERE id=?")->execute([$_POST['id']]);
            $success = "تم الحذف";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// جلب الخدمات مع المجالات
$services = $pdo->query("
    SELECT s.*, f.name_ar as field_name
    FROM services s
    LEFT JOIN specializations sp ON sp.id = s.specialization_id
    LEFT JOIN fields f ON f.id = sp.field_id
    ORDER BY s.display_order
")->fetchAll();

$fields = $pdo->query("SELECT * FROM fields ORDER BY name_ar")->fetchAll();
$specializations = $pdo->query("SELECT * FROM specializations ORDER BY name_ar")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الخدمات - بوابة خبرة</title>
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
            <h4 class="text-white mb-0"><i class="fas fa-cogs me-2"></i>إدارة الخدمات</h4>
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
            <i class="fas fa-plus me-2"></i>إضافة خدمة جديدة
        </button>
        
        <div class="card">
            <div class="card-body">
                <p class="text-muted"><strong>ملاحظة:</strong> الخدمات الحالية (36 خدمة) موجودة في الصفحات الفرعية. يمكنك إضافتها هنا لإدارتها من قاعدة البيانات.</p>
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم الخدمة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>لا توجد خدمات مسجلة في قاعدة البيانات</p>
                                <small>الخدمات الحالية موجودة في الصفحات الفرعية (36 خدمة)</small>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= $service['id'] ?></td>
                                <td><strong><?= htmlspecialchars($service['name_ar']) ?></strong></td>
                                <td><?= $service['price'] ?> ر.ع</td>
                                <td>
                                    <span class="badge bg-<?= $service['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $service['is_active'] ? 'نشط' : 'غير نشط' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick='editService(<?= json_encode($service) ?>)'>تعديل</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $service['id'] ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة خدمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>اسم الخدمة بالعربي *</label>
                            <input type="text" name="name_ar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>اسم الخدمة بالإنجليزي</label>
                            <input type="text" name="name_en" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>الأيقونة</label>
                                <input type="text" name="icon" class="form-control" placeholder="fa-code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>السعر (ر.ع)</label>
                                <input type="number" name="price" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>الفئة المستهدفة</label>
                                <select name="target_audience" class="form-select">
                                    <option value="all">الكل</option>
                                    <option value="student">طالب</option>
                                    <option value="jobseeker">باحث عن عمل</option>
                                    <option value="freelancer">صاحب عمل حر</option>
                                    <option value="institution">مؤسسة</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>الترتيب</label>
                                <input type="number" name="display_order" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>التخصص (اختياري)</label>
                            <select name="specialization_id" class="form-select">
                                <option value="">-- بدون تخصص --</option>
                                <?php foreach ($specializations as $spec): ?>
                                    <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['name_ar']) ?></option>
                                <?php endforeach; ?>
                            </select>
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
    
    <!-- Modal: تعديل خدمة -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الخدمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">اسم الخدمة بالعربي *</label>
                            <input type="text" name="name_ar" id="edit_name_ar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم الخدمة بالإنجليزي</label>
                            <input type="text" name="name_en" id="edit_name_en" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الأيقونة</label>
                                <input type="text" name="icon" id="edit_icon" class="form-control" placeholder="fa-code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">السعر (ر.ع)</label>
                                <input type="number" name="price" id="edit_price" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الفئة المستهدفة</label>
                                <select name="target_audience" id="edit_target_audience" class="form-select">
                                    <option value="all">الكل</option>
                                    <option value="student">طالب</option>
                                    <option value="jobseeker">باحث عن عمل</option>
                                    <option value="freelancer">صاحب عمل حر</option>
                                    <option value="institution">مؤسسة</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الترتيب</label>
                                <input type="number" name="display_order" id="edit_display_order" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">التخصص (اختياري)</label>
                            <select name="specialization_id" id="edit_specialization_id" class="form-select">
                                <option value="">-- بدون تخصص --</option>
                                <?php foreach ($specializations as $spec): ?>
                                    <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['name_ar']) ?></option>
                                <?php endforeach; ?>
                            </select>
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
        function editService(service) {
            document.getElementById('edit_id').value = service.id;
            document.getElementById('edit_name_ar').value = service.name_ar || '';
            document.getElementById('edit_name_en').value = service.name_en || '';
            document.getElementById('edit_description').value = service.description || '';
            document.getElementById('edit_icon').value = service.icon || '';
            document.getElementById('edit_price').value = service.price || 0;
            document.getElementById('edit_target_audience').value = service.target_audience || 'all';
            document.getElementById('edit_display_order').value = service.display_order || 0;
            document.getElementById('edit_specialization_id').value = service.specialization_id || '';
            document.getElementById('edit_is_active').value = service.is_active;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>


