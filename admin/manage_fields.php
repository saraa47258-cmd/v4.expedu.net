<?php
/**
 * إدارة المجالات
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

// معالجة الإضافة/التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO fields (name_ar, name_en, icon, description, display_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name_ar'], $_POST['name_en'], $_POST['icon'], $_POST['description'], $_POST['display_order']]);
            $success = "تم إضافة المجال بنجاح";
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE fields SET name_ar=?, name_en=?, icon=?, description=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([$_POST['name_ar'], $_POST['name_en'], $_POST['icon'], $_POST['description'], $_POST['display_order'], $_POST['is_active'], $_POST['id']]);
            $success = "تم تحديث المجال بنجاح";
        } elseif (isset($_POST['delete'])) {
            $stmt = $pdo->prepare("DELETE FROM fields WHERE id=?");
            $stmt->execute([$_POST['id']]);
            $success = "تم حذف المجال بنجاح";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// جلب جميع المجالات
$fields = $pdo->query("SELECT * FROM fields ORDER BY display_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المجالات - بوابة خبرة</title>
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
                <i class="fas fa-arrow-right me-2"></i>
                العودة للوحة التحكم
            </a>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fas fa-th-large me-2"></i>إدارة المجالات</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>
                إضافة مجال جديد
            </button>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">ID</th>
                                <th>الاسم بالعربي</th>
                                <th>الاسم بالإنجليزي</th>
                                <th>الأيقونة</th>
                                <th>الترتيب</th>
                                <th>الحالة</th>
                                <th width="150">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fields as $field): ?>
                            <tr>
                                <td><?= $field['id'] ?></td>
                                <td><strong><?= htmlspecialchars($field['name_ar']) ?></strong></td>
                                <td><?= htmlspecialchars($field['name_en']) ?></td>
                                <td><i class="fas <?= $field['icon'] ?> fa-2x"></i></td>
                                <td><span class="badge bg-secondary"><?= $field['display_order'] ?></span></td>
                                <td>
                                    <?php if ($field['is_active']): ?>
                                        <span class="badge bg-success">نشط</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">غير نشط</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editField(<?= htmlspecialchars(json_encode($field)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $field['id'] ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: إضافة مجال -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مجال جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الاسم بالعربي *</label>
                            <input type="text" name="name_ar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم بالإنجليزي</label>
                            <input type="text" name="name_en" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الأيقونة (Font Awesome)</label>
                            <input type="text" name="icon" class="form-control" placeholder="fa-network-wired">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ترتيب العرض</label>
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
    
    <!-- Modal: تعديل مجال -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المجال</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">الاسم بالعربي *</label>
                            <input type="text" name="name_ar" id="edit_name_ar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم بالإنجليزي</label>
                            <input type="text" name="name_en" id="edit_name_en" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الأيقونة</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ترتيب العرض</label>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editField(field) {
            document.getElementById('edit_id').value = field.id;
            document.getElementById('edit_name_ar').value = field.name_ar;
            document.getElementById('edit_name_en').value = field.name_en || '';
            document.getElementById('edit_icon').value = field.icon || '';
            document.getElementById('edit_description').value = field.description || '';
            document.getElementById('edit_display_order').value = field.display_order;
            document.getElementById('edit_is_active').value = field.is_active;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>


