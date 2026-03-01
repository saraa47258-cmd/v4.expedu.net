<?php
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO team_members (name, position, bio, linkedin, email, phone, display_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['position'], $_POST['bio'], $_POST['linkedin'], $_POST['email'], $_POST['phone'], $_POST['display_order']]);
            $success = "تم إضافة العضو بنجاح";
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE team_members SET name=?, position=?, bio=?, linkedin=?, email=?, phone=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([$_POST['name'], $_POST['position'], $_POST['bio'], $_POST['linkedin'], $_POST['email'], $_POST['phone'], $_POST['display_order'], $_POST['is_active'], $_POST['id']]);
            $success = "تم تحديث العضو بنجاح";
        } elseif (isset($_POST['delete'])) {
            $pdo->prepare("DELETE FROM team_members WHERE id=?")->execute([$_POST['id']]);
            $success = "تم حذف العضو";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$team = $pdo->query("SELECT * FROM team_members ORDER BY display_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الفريق - بوابة خبرة</title>
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
            <h4 class="text-white mb-0"><i class="fas fa-users me-2"></i>إدارة الفريق</h4>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible">
                <i class="fas fa-check me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between mb-3">
            <h3>أعضاء الفريق (<?= count($team) ?>)</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>إضافة عضو
            </button>
        </div>
        
        <div class="row g-3">
            <?php foreach ($team as $member): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                        <h5 class="fw-bold"><?= htmlspecialchars($member['name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($member['position']) ?></p>
                        <span class="badge bg-<?= $member['is_active'] ? 'success' : 'secondary' ?> mb-3">
                            <?= $member['is_active'] ? 'نشط' : 'غير نشط' ?>
                        </span>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-primary" onclick='editMember(<?= json_encode($member) ?>)'>تعديل</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Modals للإضافة والتعديل (مشابه للسابق) -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عضو جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الاسم *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">المنصب *</label>
                            <input type="text" name="position" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نبذة</label>
                            <textarea name="bio" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LinkedIn</label>
                            <input type="url" name="linkedin" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الهاتف</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الترتيب</label>
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
    
    <!-- Modal: تعديل عضو -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل العضو</h5>
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
                            <label class="form-label">المنصب *</label>
                            <input type="text" name="position" id="edit_position" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نبذة</label>
                            <textarea name="bio" id="edit_bio" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LinkedIn</label>
                            <input type="url" name="linkedin" id="edit_linkedin" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الهاتف</label>
                            <input type="tel" name="phone" id="edit_phone" class="form-control">
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
        function editMember(member) {
            document.getElementById('edit_id').value = member.id;
            document.getElementById('edit_name').value = member.name || '';
            document.getElementById('edit_position').value = member.position || '';
            document.getElementById('edit_bio').value = member.bio || '';
            document.getElementById('edit_linkedin').value = member.linkedin || '';
            document.getElementById('edit_email').value = member.email || '';
            document.getElementById('edit_phone').value = member.phone || '';
            document.getElementById('edit_display_order').value = member.display_order || 0;
            document.getElementById('edit_is_active').value = member.is_active;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>


